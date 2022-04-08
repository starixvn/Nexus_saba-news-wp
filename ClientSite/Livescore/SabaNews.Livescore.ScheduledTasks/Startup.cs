namespace SabaNews.Livescore.ScheduledTasks
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
    using Newtonsoft.Json;
    using SabaNews.Core.Middlewares;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Models;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Interfaces;
    using SabaNews.Livescore.SyncData.Helpers;
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
            SyncDataDI.RegisterDI(services, Configuration);

            AddConfiguration(services, Configuration);
            AddRedisConfiguration(services, Configuration);
            AddDependencyInjection(services);
            services.ResolveLivescoreServiceDI();
            AddWorker(services);
            TestCallAPIService(services);
        }

        private void TestCallAPIService(IServiceCollection services)
        {
            var apiService = services.BuildServiceProvider().GetRequiredService<IFetchMatchService>();
            var matches = apiService.FetchLiveMatchListAsync(new SyncMatchParams()).Result;
            var logger = services.BuildServiceProvider().GetRequiredService<ILogServiceProvider>();
            logger.WriteLogFileInfo(JsonConvert.SerializeObject(matches));
            logger.WriteMessageSentryErrorAsync(JsonConvert.SerializeObject(matches)).Wait();
            var pushSignalService = services.BuildServiceProvider().GetRequiredService<IPushSignalService>();
            pushSignalService.PushMatchListAsync(new List<Match>());
        }

        private void AddRedisConfiguration(IServiceCollection services, IConfiguration configuration)
        {
            services.AddRedisCache(configuration.GetSection("RedisConfig"));

            services.AddDataProtection()
                .SetApplicationName(Configuration.GetSection("RedisConfig:ApplicationName").Value)
                .PersistKeysToStackExchangeRedis(Redis, "SabaSports-Keys");

            services.AddStackExchangeRedisCache(options =>
            {
                options.Configuration = Configuration.GetSection("RedisConfig:ConnectionString").Value;
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

            var jobSettings = configuration.GetSection(ScheduledTaskSettings.ConfigurationKey).Get<List<ScheduledTaskSettings>>();
            services.AddSingleton(jobSettings);

            var appSettings = configuration.GetSection("AppSettings").Get<AppSettings>();
            services.AddSingleton(appSettings);

            var signalRSettings = configuration.GetSection(PushSignalSettings.ConfigurationKey).Get<PushSignalSettings>();
            services.AddSingleton(signalRSettings);
        }

        private static void AddDependencyInjection(IServiceCollection services)
        {
            services.AddSingleton<IFetchMatchService, FetchMatchService>();
            services.AddSingleton<ILiveMatchService, LiveMatchService>();
        }

        private static void AddWorker(IServiceCollection services)
        {
            services.AddHostedService<SyncLeagueListByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncLeagueListGreaterThanLeagueIdWorker>();
            services.AddHostedService<SyncTeamListByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncTeamListGreaterThanTeamIdWorker>();
            services.AddHostedService<SyncPlayerListByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncSeasonListByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncStageListByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncSeasonStandingsByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncSeasonTeamHonorByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncSeasonPlayerHonorByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncMatchByLastUpdatedTimeWorker>();
            services.AddHostedService<SyncMatchInfoByMatchIdsWorker>();
            services.AddHostedService<SyncLiveMatchThenPushSignalWorker>();
            services.AddHostedService<SyncHeadToHeadFromCurrentDateToFutureWorker>();
        }
    }
}