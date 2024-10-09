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
    using SabaNews.Core.Helper;
    using SabaNews.Core.Middlewares;
    using SabaNews.Core.Models;
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
            services.AddResponseCaching();
            services.AddSingleton(Configuration);

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
            
            if (env.IsDevelopment())
            {
                app.UseDeveloperExceptionPage();
            }
            else
            {
                app.UseMiddleware<ErrorHandler>();
                app.UseHsts();
                app.UseHttpsRedirection();

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
            app.UseResponseCaching();
            app.UseResponseCompression();
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

            app.UseRouting();

            app.UseCookiePolicy();
            app.UseAuthentication();
            app.UseAuthorization();
            app.UseSession();

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
    }
}