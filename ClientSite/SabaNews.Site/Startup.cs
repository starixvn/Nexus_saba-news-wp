namespace SabaNews
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text.Encodings.Web;
    using System.Text.Unicode;
    using System.Threading;
    using Fanex.Caching.Redis;
    using Fanex.Data;
    using Fanex.Data.MySql;
    using Fanex.Logging;
    using Fanex.Logging.Extensions;
    using Fanex.Logging.Extensions.AspNetCore;
    using Fanex.Logging.Extensions.Database;
    using Fanex.Logging.Sentry;
    using Microsoft.AspNetCore.Builder;
    using Microsoft.AspNetCore.DataProtection;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.AspNetCore.Http.Connections;
    using Microsoft.AspNetCore.HttpOverrides;
    using Microsoft.AspNetCore.ResponseCompression;
    using Microsoft.AspNetCore.Routing;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using Microsoft.Extensions.Hosting;
    using SabaNews.Core.Extensions;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Middlewares;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Services;
    using SabaNews.Site.Extensions;
    using SabaNews.Site.Hubs;
    using SabaNews.Site.Middlewares;
    using Sentry;
    using StackExchange.Redis;

    public class Startup
    {
        private readonly ConnectionMultiplexer Redis;
        private const int NotFoundCode = 404;

        public Startup(IConfiguration configuration)
        {
            Configuration = configuration;
            Redis = ConnectionMultiplexer.Connect(Configuration.GetSection("RedisConfig:ConnectionString").Value);
        }

        public IConfiguration Configuration { get; }

        public void ConfigureServices(IServiceCollection services)
        {
            var connections = Configuration.GetSection("ConnectionStrings")
                         .GetChildren()
                         .AsEnumerable()
                         .Where(x => !string.IsNullOrWhiteSpace(x.GetValue<string>("Name")))
                         .ToDictionary(x => x.GetValue<string>("Name"),
                             y => new ConnectionConfiguration(y.GetValue<string>("Name"), y.GetValue<string>("ConnectionString"), y.GetValue<string>("ProviderName")));

            DbSettingProviderManager
               .StartNewSession()
               .Use(connections)
               .WithMySql()
               .UseDefaultDbSettingProvider(resourcePath: "~/App_Data/SpNames.xml")
               .Run();

            LogManager
               .SetDefaultLogCategory(Configuration.GetSection("LogCategory").Value)
               .Use(new List<ILogEngine>
               {
                   new SentryLogging(new SentryEngineOptions
                   {
                       Dsn = new Dsn(Configuration.GetSection("SentryEngineOptions:Dsn").Value),
                       FrontEndDsn = new Dsn(Configuration.GetSection("SentryEngineOptions:FrontEndDsn").Value)
                   })
               });

            ExceptionHandler
                .UseConfig(new LoggingConfiguration())
                .UseGetDbExceptionInfo(DatabaseInfoGetters.GetDbExceptionInfo);

            services.AddAntiforgery(o => o.HeaderName = "XSRF-TOKEN");

            services.Configure<ForwardedHeadersOptions>(options =>
            {
                options.ForwardedHeaders =
                    ForwardedHeaders.XForwardedFor | ForwardedHeaders.XForwardedProto;
            });

            var redisConfig = Configuration.GetSection("RedisConfig:ConnectionString").Value;
            var appName = Configuration.GetSection("RedisConfig:ApplicationName").Value;
            services.AddDataProtection()
                .SetApplicationName(appName)
                .PersistKeysToStackExchangeRedis(Redis, $"{appName}:DataProtection:Keys");

            services.AddStackExchangeRedisCache(options =>
            {
                options.Configuration = redisConfig;
                options.InstanceName = $"{appName}:Session:";
            });

            services.AddSignalR(hubOptions =>
            {
                hubOptions.EnableDetailedErrors = true;
                hubOptions.MaximumReceiveMessageSize = int.Parse(Configuration.GetSection("SignalRMaximumReceiveMessageSize").Value);
            }).AddStackExchangeRedis(redisConfig, options =>
            {
                options.Configuration.ChannelPrefix = $"{appName}:SignalR";
            });

            services.RegisterCookiePolicy();

            services.RegisterAuthentication(Configuration);

            services.AddSession(options =>
            {
                options.Cookie.Name = ".SabaSports.Session";
                options.IdleTimeout = TimeSpan.FromMinutes(int.Parse(Configuration.GetSection("Authentication:SessionTimeOut").Value));
                options.Cookie.IsEssential = true;
            });
            services.AddRedisCache(Configuration.GetSection("RedisConfig"));

            services.Configure<MailConfigs>(options => Configuration.GetSection("MailConfigs").Bind(options));
            services.Configure<LivescoreMainPageCacheConfig>(options => Configuration.GetSection("LivescoreMainPageCacheConfig").Bind(options));
            services.Configure<RouteOptions>(options => {
                options.ConstraintMap.Add("categoryslug", typeof(CategoryConstraint));
            });

            services.AddResponseCompression( options => {
                options.MimeTypes = ResponseCompressionDefaults.MimeTypes.Concat(
                    new[] { "text/javascript"}
                );
            });
            ConfigWebOptimizer(services);
            services.AddResponseCaching();
            services.AddSingleton(Configuration);

            CoreDI.RegisterService(services);
            services.ResolveLivescoreServiceDI();
            services.AddSingleton(HtmlEncoder.Create(allowedRanges: UnicodeRanges.All ));
        
            var umSettings = Configuration.GetSection(UMSettings.SettingKey).Get<UMSettings>();
            services.AddSingleton(umSettings);

            services.RegisterServices();
            services.AddHttpClient();
            services.AddControllersWithViews();

            const short WorkerThreads = 300;
            const short CompletionPortThreads = 300;
            _ = ThreadPool.SetMinThreads(WorkerThreads, CompletionPortThreads);
        }

        public void Configure(IApplicationBuilder app, IWebHostEnvironment env)
        {
            var isUM = bool.Parse(Configuration.GetSection("UMSettings:Enabled").Value);
            if (isUM)
            {
                app.UseWhen(
                context => !context.Request.Path.StartsWithSegments("/maintenance", StringComparison.OrdinalIgnoreCase),
                appBuilder =>
                {
                    appBuilder.UseMiddleware<MaintenanceMiddleware>();
                });
            }

            if (env.IsDevelopment())
            {
                app.UseDeveloperExceptionPage();
            }
            else
            {
                app.UseMiddleware<ErrorHandler>();
                app.UseHsts();

                app.Use((context, next) =>
                {
                    context.Response.Headers.Remove("X-Frame-Options");
                    context.Response.Headers["X-Frame-Options"] = "AllowAll";

                    if (context.Request.Headers["x-forwarded-proto"] == "https")
                    {
                        context.Request.Scheme = "https";
                    }
                    return next();
                });
            }
            app.Use(async (ctx, next) =>
            {
                await next();

                if (ctx.Response.StatusCode == NotFoundCode && !ctx.Response.HasStarted)
                {
                    ctx.Request.Path = "/404";
                    await next();
                }
            });
            app.UseForwardedHeaders(new ForwardedHeadersOptions
            {
                ForwardedHeaders = ForwardedHeaders.XForwardedFor | ForwardedHeaders.XForwardedProto | ForwardedHeaders.All
            });
            app.UseResponseCompression();
            app.UseWebOptimizer();
            app.UseStaticFiles(new StaticFileOptions
            {
                OnPrepareResponse = context =>
                {
                    // Cache static file for 1 year
                    if (!string.IsNullOrEmpty(context.Context.Request.Query["v"]))
                    {
                        context.Context.Response.Headers.Add("cache-control", new[] { "public,max-age=31536000" });
                        context.Context.Response.Headers.Add("Expires", new[] { DateTimeUtilize.UtcNow().AddYears(1).ToString("R") }); // Format RFC1123
                    }
                }
            });

            app.UseCookiePolicy();
            app.UseRouting();
            app.UseSession();
            app.UseAuthentication();
            app.UseAuthorization();

            app.UseResponseCaching();

            app.UseHttpsRedirection();

            app.UseEndpoints(endpoints =>
            {
                endpoints.MapControllerRoute(
                   name: "Admin",
                   pattern: "{area:exists}/{controller=Statistic}/{action=Index}/{id?}");

                endpoints.MapControllerRoute(
                    name: "default",
                    pattern: "{controller=Home}/{action=Index}/{id?}");

                endpoints.MapHub<LivescoreHub>("/livescore/hubs", options =>
                {
                    options.Transports = HttpTransportType.WebSockets;
                });
            });
        }


        private static void ConfigWebOptimizer(IServiceCollection services)
        {
            
            services.AddWebOptimizer(pipeline => {
                /* Css */
                pipeline.AddCssBundle("/minify/styles/site.min.css",
                    "/styles/pages/auth.css"
                );
                pipeline.AddCssBundle("/minify/styles/home.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/home.css"
                );
                pipeline.AddCssBundle("/minify/styles/news.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/news.css"
                );
                pipeline.AddCssBundle("/minify/styles/notfound.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/notfound.css"
                );
                pipeline.AddCssBundle("/minify/styles/profile.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/profile/profile.css"
                );
                pipeline.AddCssBundle("/minify/styles/admin_email.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/admin/email/index.css"
                );
                pipeline.AddCssBundle("/minify/styles/admin_email_create.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/lib/bootstrap/multiselect/css/bootstrap-multiselect.css",
                    "/styles/pages/admin/email/update.css"
                );
                pipeline.AddCssBundle("/minify/styles/video.min.css",
                    "/lib/swipper/css/swiper.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/video.css"
                );
                pipeline.AddCssBundle("/minify/styles/detailnews.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/detailnews.css",
                    "/styles/pages/super-web-share-public.css"
                );
                pipeline.AddCssBundle("/minify/styles/maintenance.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/maintenance.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/euro2020.css",
                    "/lib/swipper/css/swiper.min.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020_home.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/Euro2020/home.css",
                    "/lib/swipper/css/swiper.min.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020_video.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/Euro2020/video.css",
                    "/lib/swipper/css/swiper.min.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020_category.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/Euro2020/category.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020_schedule.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/Euro2020/schedule.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020_team.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/Euro2020/team.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020_ranking.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/Euro2020/ranking.css"
                );
                pipeline.AddCssBundle("/minify/styles/euro2020_detail.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/Euro2020/detail.css"
                );
                pipeline.AddCssBundle("/minify/styles/membership-event.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/membership-event.css"
                );
                pipeline.AddCssBundle("/minify/styles/admin.min.css",
                    "/lib/bootstrap/dist/css/bootstrap.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/admin/admin.css"
                );
                pipeline.AddCssBundle("/minify/styles/admin_banner.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/admin/banner/index.css"
                );
                pipeline.AddCssBundle("/minify/styles/worldcup2022.min.css",
                    "/lib/swipper/css/swiper-bundle.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/event/worldcup2022/index.css"
                );
                pipeline.AddCssBundle("/minify/styles/worldcup2022-fixture.min.css",
                    "/lib/swipper/css/swiper-bundle.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/event/worldcup2022/fixture.css"
                );
                pipeline.AddCssBundle("/minify/styles/lastestnews.min.css",
                    "/styles/pages/event/worldcup2022/lastestnews.css"
                );
                pipeline.AddCssBundle("/minify/styles/detailstanding.min.css",
                    "/styles/pages/event/worldcup2022/detailstanding.css"
                );
                pipeline.AddCssBundle("/minify/styles/videoworldcup2022.min.css",
                    "/styles/pages/event/worldcup2022/video.css"
                );
                pipeline.AddCssBundle("/minify/styles/postdetail-worldcuop-2022.min.css",
                    "/styles/pages/event/worldcup2022/postdetail-wc.css"
                );
                pipeline.AddCssBundle("/minify/styles/worldcup2022-story-post.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/event/worldcup2022/story-post.css"
                );
                pipeline.AddCssBundle("/minify/styles/worldcup2022-story-post-detail.min.css",
                    "/styles/vendor/icomoon/style.css",
                    "/styles/pages/event/worldcup2022/story-post-detail.css"
                );

                /* Js */

                pipeline.AddJavaScriptBundle("/minify/js/site.min.js",
                    "/lib/jquery/dist/jquery.min.js",
                    "/lib/bootstrap/dist/js/bootstrap-modal.min.js",
                    "/js/common.js",
                    "/js/site.js",
                    "/js/all.js",
                    "/js/auth/auth.js",
                    "/js/post/load-component.js"
                );

                pipeline.AddJavaScriptBundle("/minify/js/swiper.min.js",
                    "/lib/swipper/js/swiper.min.js",
                    "/js/swiper.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/home.min.js",
                    "/js/home.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/blog.min.js",
                    "/js/post/blog.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/notfound.min.js",
                    "/js/post/notfound.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/reset-password.min.js",
                    "/lib/jquery-validation/dist/jquery.validate.js",
                    "/js/auth/reset-password.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/single.min.js",
                    "/js/profile/profile.js",
                    "/js/comment.js",
                    "/js/post/poll.js",
                    "/js/post/single.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/update_profile.min.js",
                    "/js/profile/update_profile.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/membership-event.min.js",
                    "/js/membership-event/membership-event.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin_statistic_post_view.min.js", 
                    "/lib/jquery/dist/jquery.min.js",
                    "/js/admin/statistic/post-view-statistic.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin_statistic_view_detail.min.js", 
                    "/lib/jquery/dist/jquery.min.js",
                    "/js/admin/statistic/view-statistic-details.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin_email.min.js",
                    "/lib/jquery-blockUI/jquery.blockUI.js",
                    "/js/admin/email/index.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin_email_create.min.js",
                    "/lib/jquery-blockUI/jquery.blockUI.js",
                    "/js/lib/config_ckeditor.js",
                    "/lib/bootstrap/multiselect/js/bootstrap-multiselect.js",
                    "/js/admin/email/create.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin_email_detail.min.js",
                    "/lib/jquery-blockUI/jquery.blockUI.js",
                    "/js/admin/email/details.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin.min.js",
                    "/lib/jquery/dist/jquery.min.js",
                    "/lib/bootstrap/dist/js/bootstrap.bundle.js",
                    "/js/common.js",
                    "/js/admin/admin.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin_user.min.js",
                    "/js/admin/user/user.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/admin_banner.min.js",
                    "/js/admin/banner/index.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/worldcup2022.min.js",
                    "/lib/swipper/js/swiper-bundle.min.js",
                    "/js/event/worldcup2022/worldcup.js",
                    "/js/event/worldcup2022/index.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/lastestnews.min.js",
                    "/js/event/worldcup2022/lastestnews.js",
                    "/js/event/worldcup2022/scroll.js",
                    "/js/event/worldcup2022/worldcup.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/worldcup2022-fixture.min.js", 
                    "/lib/swipper/js/swiper-bundle.min.js",
                    "/js/event/worldcup2022/worldcup.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/standingworldcup2022.min.js", 
                    "/js/event/worldcup2022/Standing.js",
                    "/js/event/worldcup2022/worldcup.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/videoworldcup2022.min.js",
                    "/lib/swipper/js/swiper-bundle.min.js",
                    "/js/event/worldcup2022/video.js",
                    "/js/event/worldcup2022/scroll.js",
                    "/js/event/worldcup2022/worldcup.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/worldcup-2022-story-post.min.js", 
                    "/js/event/worldcup2022/scroll.js",
                    "/js/event/worldcup2022/worldcup.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/worldcup-2022-story-post-detail.min.js", 
                    "/js/event/worldcup2022/story-post-detail.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/postdetail-worldcup-2022.min.js", 
                    "/js/event/worldcup2022/worldcup.js",
                    "/js/event/worldcup2022/lastestnews.js",
                    "/js/profile/profile.js",
                    "/js/comment.js",
                    "/js/post/poll.js",
                    "/js/post/single.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/minigame-worldcuop-2022.min.js", 
                    "/js/event/worldcup2022/worldcup.js",
                    "/js/event/worldcup2022/minigame.js"
                );
                pipeline.AddJavaScriptBundle("/minify/js/worldcup2022-match.min.js",
                    "/lib/swipper/js/swiper-bundle.min.js",
                    "/js/event/worldcup2022/worldcup.js"
                );

                pipeline.MinifyCssFiles();
                pipeline.MinifyJsFiles();
            });
        }
    }
}