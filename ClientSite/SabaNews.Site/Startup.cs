namespace SabaNews
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using Fanex.Caching;
    using Fanex.Caching.Redis;
    using Fanex.Data;
    using Fanex.Data.MySql;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Fanex.Logging.Extensions;
    using Fanex.Logging.Extensions.AspNetCore;
    using Fanex.Logging.Extensions.Database;
    using Fanex.Logging.Sentry;
    using Hangfire;
    using Hangfire.Redis;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Builder;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.HttpOverrides;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using Microsoft.Extensions.Hosting;
    using sabanews.Middlewares;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using Sentry;
    using StackExchange.Redis;

    public class Startup
    {
        private readonly ConnectionMultiplexer Redis;

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

            services.Configure<ForwardedHeadersOptions>(options =>
            {
                options.ForwardedHeaders =
                    ForwardedHeaders.XForwardedFor | ForwardedHeaders.XForwardedProto;
            });

            services.Configure<CookiePolicyOptions>(options =>
            {
                options.CheckConsentNeeded = context => true;
                options.MinimumSameSitePolicy = SameSiteMode.None;
            });

            services.AddAuthentication(options=>
            {
                options.DefaultChallengeScheme = CookieAuthenticationDefaults.AuthenticationScheme;
                options.DefaultSignInScheme = CookieAuthenticationDefaults.AuthenticationScheme;
                options.DefaultAuthenticateScheme = CookieAuthenticationDefaults.AuthenticationScheme;
            }).AddCookie(options =>
            {
                options.Cookie.Name = ".SabaSports.Cookies";
            });

            services.AddSession(options =>
            {
                options.Cookie.Name = ".SabaSports.Session";
                options.Cookie.IsEssential = true;
            });

            services.Configure<List<JobSetting>>(options => Configuration.GetSection("HangfireConfig:JobSettings").Bind(options));
            services.AddRedisCache(Configuration.GetSection("RedisConfig"));

            services.Configure<MailConfigs>(options => Configuration.GetSection("MailConfigs").Bind(options));

            services.AddResponseCompression();
            services.AddResponseCaching();
            services.AddDetection();
            services.AddControllersWithViews();
            services.AddSingleton(Configuration);

            services.AddSingleton<IBaseService, BaseService>();
            services.AddSingleton<IBaseRepository, BaseRepository>();
            services.AddSingleton<IDynamicRepository, DynamicRepository>();
            services.AddSingleton<ILogger, Logger>();
            services.AddSingleton<IBaseRepository, BaseRepository>();
            services.AddSingleton<ILogServiceProvider, LogServiceProvider>();
            services.AddScoped<IBaseMailService, BaseMailService>();
            services.AddScoped<ISessionService, SessionService>();

            services.AddTransient<ISerializerService, SerializerService>();
            services.AddTransient<IAuthService, AuthService>();
            services.AddTransient<IPostService, PostService>();
            services.AddTransient<ICommentService, CommentService>();
            services.AddTransient<IPathService, PathService>();
            services.AddTransient<ICacheService, CacheService>();
            services.AddTransient<IRecurringJobManager, RecurringJobManager>();

            services.AddTransient<IFetchDataService, FetchDataService>();
            services.AddTransient<IRefreshCachingService, RefreshCachingService>();
            services.AddTransient<IDataControlService, DataControlService>();
            services.AddTransient<IBackgroundJobService, BackgroundJobService>();


            services.AddHangfire(x =>
            {
                x.UseRedisStorage(Redis, new RedisStorageOptions
                {
                    Prefix = Configuration.GetValue<string>("HangfireConfig:Prefix"),
                    SucceededListSize = Configuration.GetValue<int>("HangfireConfig:SucceededListSize"),
                    DeletedListSize = Configuration.GetValue<int>("HangfireConfig:DeletedListSize"),
                    Db = Configuration.GetValue<int>("HangfireConfig:Db")
                });
            });
        }

        public void Configure(IApplicationBuilder app, IWebHostEnvironment env)
        {
            if (env.IsDevelopment())
            {
                app.UseForwardedHeaders();
                app.UseDeveloperExceptionPage();
            }
            else
            {
                app.UseMiddleware<ErrorHandler>();
                app.UseForwardedHeaders();
                app.UseHsts();
            }

            app.UseHttpsRedirection();
            app.UseResponseCompression();
            app.UseStaticFiles(new StaticFileOptions
            {
                OnPrepareResponse = context =>
                {
                    // Cache static file for 1 year
                    if (!string.IsNullOrEmpty(context.Context.Request.Query["v"]))
                    {
                        context.Context.Response.Headers.Add("cache-control", new[] { "public,max-age=31536000" });
                        context.Context.Response.Headers.Add("Expires", new[] { DateTime.UtcNow.AddYears(1).ToString("R") }); // Format RFC1123
                    }
                }
            });

            app.UseRouting();
            app.UseResponseCaching();
            app.UseAuthentication();
            app.UseSession();

            if (Configuration.GetValue<bool>("HangfireConfig:IsUseDashboard"))
            {
                app.UseHangfireDashboard().UseHangfireServer(options: new BackgroundJobServerOptions
                {
                    WorkerCount = Configuration.GetValue<int>("HangfireConfig:WorkerCount"),
                    ServerName = Environment.MachineName
                });
            }

            app.UseEndpoints(endpoints =>
            {
                endpoints.MapControllerRoute(
                    name: "default",
                    pattern: "{controller=Home}/{action=Index}/{id?}");
            });

            RunHangfireJob(app);
        }

        private static void RunHangfireJob(IApplicationBuilder app)
        {
            var serviceProvider = app.ApplicationServices;
            var backgroundService = serviceProvider.GetService<IBackgroundJobService>();
            backgroundService.TriggerUpdateCaching();
        }
    }
}