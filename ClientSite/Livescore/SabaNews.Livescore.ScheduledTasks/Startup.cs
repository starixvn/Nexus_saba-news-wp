namespace SabaNews.AutoJobs
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Net;
    using System.Net.Http;
    using Fanex.Caching.Redis;
    using Fanex.Data;
    using Fanex.Data.MySql;
    using Fanex.Data.Repository;
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
    using SabaNews.Core.Middlewares;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Constants;
    using SabaNews.Livescore.Provider.API.Models;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.Provider.API.Services.Interfaces;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Services;
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
            RegisterServices(services, Configuration);
            AddConfiguration(services, Configuration);
            AddRedisConfiguration(services, Configuration);
            AddDependencyInjection(services);
            AddWorker(services);
        }

        private void AddRedisConfiguration(IServiceCollection services, IConfiguration configuration)
        {
            services.AddRedisCache(configuration.GetSection("RedisConfig"));

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
            services.Configure<List<ScheduledTaskSettings>>(configuration.GetSection("JobSettings"));
        }

        private static void AddDependencyInjection(IServiceCollection services)
        {
            services.AddSingleton<IFetchMatchService, FetchMatchService>();
        }

        private static void AddWorker(IServiceCollection services)
        {
            var apiService = services.BuildServiceProvider().GetRequiredService<IFetchMatchService>();
        }

        public static void RegisterServices(IServiceCollection services, IConfiguration configuration)
        {
            RegisterHttpClientService(services, configuration);
            RegisterConfiguration(services, configuration);

            services.AddSingleton<ILogger, Logger>();
            services.AddSingleton<ILogServiceProvider, LogServiceProvider>();
            services.AddSingleton<IBaseService, BaseService>();
            services.AddSingleton<IDynamicRepository, DynamicRepository>();
            services.AddSingleton<IBaseRepository, BaseRepository>();
        }

        private static void RegisterHttpClientService(IServiceCollection services, IConfiguration configuration)
        {
            var httpConfig = configuration.GetSection(HttpClientConfigurations.ConfigurationKey).Get<HttpClientConfigurations>();
            services.AddHttpClient(HttpClientName.DefaultHttpClientWithProxy).ConfigurePrimaryHttpMessageHandler(() => new SocketsHttpHandler
            {
                UseProxy = httpConfig.IsUseProxy,
                Proxy = new WebProxy(httpConfig.Proxy, httpConfig.ByPassLocal)
            });

            services.AddSingleton<IHttpClientService, HttpClientService>();
        }

        private static void RegisterConfiguration(IServiceCollection services, IConfiguration configuration)
        {
            var apiConfiguration = configuration.GetSection(ApiConfiguration.ConfigurationKeyName).Get<ApiConfiguration>();
            services.AddSingleton(apiConfiguration);
        }
    }
}