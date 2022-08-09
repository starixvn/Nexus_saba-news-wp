namespace SabaNews.AutoJobs
{
    using System.Collections.Generic;
    using System.Linq;
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
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.AutoJobs.Models;
    using SabaNews.AutoJobs.Workers;
    using SabaNews.Core.Extensions;
    using SabaNews.Core.Middlewares;
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
            services.AddControllers();
            AddConfiguration(services, Configuration);
            AddRedisConfiguration(services);
            AddDependencyInjection(services);
            AddWorker(services);
        }

        private void AddRedisConfiguration(IServiceCollection services)
        {
            services.AddDataProtection()
                .SetApplicationName(Configuration.GetSection("RedisConfig:ApplicationName").Value)
                .PersistKeysToStackExchangeRedis(Redis, "SabaSports-Keys");
        }

        public static void Configure(IApplicationBuilder app, IWebHostEnvironment env)
        {
            app.UseMiddleware<ErrorHandler>();
            app.UseHsts();

            app.UseStaticFiles();
            app.UseRouting();
            app.UseEndpoints(endpoints =>
            {
                endpoints.MapControllerRoute(
                    name: "default",
                    pattern: "{controller=Auth}/{action=Index}/{id?}");
            });
        }

        private static void AddConfiguration(IServiceCollection services, IConfiguration configuration)
        {
            var connections = configuration.GetSection("ConnectionStrings")
                                    .GetChildren()
                                    .AsEnumerable()
                                    .Where(x => !string.IsNullOrWhiteSpace(x.GetValue<string>("Name")))
                                    .ToDictionary(x => x.GetValue<string>("Name"),
                                    y => new ConnectionConfiguration(y.GetValue<string>("Name"), y.GetValue<string>("ConnectionString"), y.GetValue<string>("ProviderName")));

            DbSettingProviderManager
                       .StartNewSession()
                       .Use(connections)
                       .WithMySql()
                       .UseDefaultDbSettingProvider("~/App_Data/SpNames.xml")
                       .Run();

            LogManager
            .SetDefaultLogCategory(configuration.GetSection("LogCategory").Value)
            .Use(new List<ILogEngine>
            {
                new SentryLogging(new SentryEngineOptions
                {
                    Dsn = new Dsn(configuration.GetSection("SentryEngineOptions:Dsn").Value)
                })
            });

            ExceptionHandler
                .UseConfig(new LoggingConfiguration())
                .UseGetDbExceptionInfo(DatabaseInfoGetters.GetDbExceptionInfo);

            services.AddSingleton(configuration);
            services.Configure<List<JobSettings>>(configuration.GetSection("JobSettings"));

            services.AddRedisCache(configuration.GetSection("RedisConfig"));
        }

        private static void AddDependencyInjection(IServiceCollection services)
        {
            CoreDI.RegisterService(services);

            services.AddTransient<ISerializerService, SerializerService>();
            services.AddTransient<IPostService, PostService>();
            services.AddTransient<IBannerService, BannerService>();

            services.AddTransient<IFetchDataService, FetchDataService>();
            services.AddTransient<IRefreshCachingService, RefreshCachingService>();
        }

        private static void AddWorker(IServiceCollection services)
        {
            services.AddHostedService<RefreshHomePageCachingWorker>();
            services.AddHostedService<RefreshCategoryCachingWorker>();
            services.AddHostedService<RefreshMenuCachingWorker>();
            services.AddHostedService<RefreshNewsSitemapXmlFileWorker>();
            services.AddHostedService<RefreshRssWorker>();
            services.AddHostedService<RefreshSitemapXmlFileWorker>();
            services.AddHostedService<RefreshBannerWorker>();
            services.AddHostedService<RefreshWorldCupCachingWorker>();
        }
    }
}