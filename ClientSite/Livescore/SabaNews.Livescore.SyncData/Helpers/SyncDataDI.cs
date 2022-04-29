namespace SabaNews.Livescore.SyncData.Helpers
{
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Core.Extensions;
    using SabaNews.Livescore.Provider.API.Helpers;
    using SabaNews.Livescore.SyncData.Services;
    using SabaNews.Livescore.SyncData.Services.Interfaces;

    public static class SyncDataDI
    {
        public static void RegisterDI(IServiceCollection services, IConfiguration configuration)
        {
            CoreDI.RegisterService(services);
            ProviderApiHelpers.RegisterServices(services, configuration);
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
            services.AddTransient<ISyncMqttRecivedDataService, SyncMqttRecivedDataService>();
        }
    }
}