namespace SabaNews.Livescore.SyncData.Helpers
{
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Microsoft.AspNetCore.Http;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Helpers;
    using SabaNews.Livescore.SyncData.Services;
    using SabaNews.Livescore.SyncData.Services.Interfaces;
    using SabaNews.Services;

    public static class SyncDataDI
    {
        public static void RegisterDI(IServiceCollection services, IConfiguration configuration)
        {
            ProviderApiHelpers.RegisterDI(services, configuration);
            RegisterCoreDI(services);
            RegisterServices(services);
        }

        private static void RegisterServices(IServiceCollection services)
        {
            services.AddTransient<ISyncLeagueService, SyncLeagueService>();
            services.AddTransient<ISyncTeamService, SyncTeamService>();
            services.AddTransient<ISyncPlayerService, SyncPlayerService>();
            services.AddTransient<ISyncSeasonService, SyncSeasonService>();
            services.AddTransient<ISyncMatchService, SyncMatchService>();
            services.AddTransient<ISyncHeadToHeadService, SyncHeadToHeadService>();
        }

        private static void RegisterCoreDI(IServiceCollection services)
        {
            services.AddSingleton<IBaseService, BaseService>();
            services.AddSingleton<IDynamicRepository, DynamicRepository>();
            services.AddSingleton<IBaseRepository, BaseRepository>();
            services.AddSingleton<IHttpContextAccessor, HttpContextAccessor>();
            services.AddScoped<ILogger, Logger>();
            services.AddSingleton<ILogServiceProvider, LogServiceProvider>();
        }
    }
}