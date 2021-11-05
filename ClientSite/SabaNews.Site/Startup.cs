namespace SabaNews
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text.Encodings.Web;
    using System.Text.Unicode;
    using Fanex.Caching.Redis;
    using Fanex.Data;
    using Fanex.Data.MySql;
    using Fanex.Logging;
    using Fanex.Logging.Extensions;
    using Fanex.Logging.Extensions.AspNetCore;
    using Fanex.Logging.Extensions.Database;
    using Fanex.Logging.Sentry;
    using Hangfire;
    using Hangfire.Redis;
    using Microsoft.AspNetCore.Builder;
    using Microsoft.AspNetCore.DataProtection;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.AspNetCore.HttpOverrides;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using Microsoft.Extensions.Hosting;
    using sabanews.Middlewares;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;
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

            var redisConfig = Configuration.GetSection("RedisConfig:ConnectionString").Value;
            var redis = ConnectionMultiplexer.Connect(redisConfig);
            services.AddDataProtection()
                .SetApplicationName(Configuration.GetSection("RedisConfig:ApplicationName").Value)
                .PersistKeysToStackExchangeRedis(redis, "SabaSports-Keys");

            services.AddStackExchangeRedisCache(options =>
            {
                options.Configuration = redisConfig;
                options.InstanceName = $"SabaSports.Session-";
            });

            services.RegisterCookiePolicy();
           

            services.RegisterAuthentication(Configuration);

            services.AddSession(options =>
            {
                options.Cookie.Name = ".SabaSports.Session";
                options.IdleTimeout = TimeSpan.FromMinutes(int.Parse(Configuration.GetSection("Authentication:SessionTimeOut").Value));
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

            services.RegisterCoreDI();
            services.AddSingleton<ILogger, Logger>();
            services.AddSingleton<ILogServiceProvider, LogServiceProvider>();
            services.AddSingleton<HtmlEncoder>(
            HtmlEncoder.Create(allowedRanges: new[] {
                UnicodeRanges.All
           }));

            services.RegisterServices();
            services.AddRazorPages();

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
                app.UseDeveloperExceptionPage();
                app.UseForwardedHeaders();
            }
            else
            {
                app.UseMiddleware<ErrorHandler>();
                app.UseForwardedHeaders();
                app.UseHsts();

                app.Use((context, next) =>
                {
                    if (context.Request.Headers["x-forwarded-proto"] == "https")
                    {
                        context.Request.Scheme = "https";
                    }
                    return next();
                });
            }
            app.UseForwardedHeaders(new ForwardedHeadersOptions
            {
                ForwardedHeaders = ForwardedHeaders.XForwardedFor | ForwardedHeaders.XForwardedProto | ForwardedHeaders.All
            });
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

            app.UseCookiePolicy();
            app.UseRouting();
            app.UseSession();
            app.UseAuthentication();
            app.UseAuthorization();

            app.UseResponseCaching();

            app.UseHttpsRedirection();

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
                   name: "Admin",
                   pattern: "{area:exists}/{controller=Statistic}/{action=Index}/{id?}");

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