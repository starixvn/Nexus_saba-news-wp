namespace SabaNews
{
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
    using Microsoft.AspNetCore.Builder;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.HttpOverrides;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using Microsoft.Extensions.Hosting;
    using sabanews.Middlewares;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Core.Models;
    using Sentry;
    using StackExchange.Redis;
    using System;
    using System.Collections.Generic;
    using System.Linq;

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
            services.Configure<List<JobSetting>>(options => Configuration.GetSection("HangfireConfig:JobSettings").Bind(options));
            services.AddRedisCache(Configuration.GetSection("RedisConfig"));

            services.AddResponseCompression();
            services.AddResponseCaching();
            services.AddControllersWithViews();
            services.AddSingleton(Configuration);

            services.AddSingleton<IBaseService, BaseService>();
            services.AddSingleton<IDynamicRepository, DynamicRepository>();
            services.AddScoped<ILogger, Logger>();

            services.AddTransient<IPostService, PostService>();
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
            app.UseStaticFiles();
            app.UseRouting();
            app.UseResponseCaching();

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