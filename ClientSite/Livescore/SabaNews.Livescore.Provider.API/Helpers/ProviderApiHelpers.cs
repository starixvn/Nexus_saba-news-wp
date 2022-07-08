namespace SabaNews.Livescore.Provider.API.Helpers
{
    using System.Net;
    using System.Net.Http;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Livescore.Core.Constants;
    using SabaNews.Livescore.Provider.API.Models;
    using SabaNews.Livescore.Provider.API.Services;

    public static class ProviderApiHelpers
    {
        public static void RegisterServices(IServiceCollection services, IConfiguration configuration)
        {
            RegisterHttpClientService(services, configuration);
            RegisterConfiguration(services, configuration);

            services.AddTransient<IFetchLeagueService, FetchLeagueService>();
            services.AddTransient<IFetchTeamService, FetchTeamService>();
            services.AddTransient<IFetchPlayerService, FetchPlayerService>();
            services.AddTransient<IFetchSeasonService, FetchSeasonService>();
            services.AddTransient<IFetchMatchService, FetchMatchService>();
            services.AddTransient<IFetchCountryService, FetchCountryService>();
        }

        private static void RegisterHttpClientService(IServiceCollection services, IConfiguration configuration)
        {
            var httpConfig = configuration.GetSection(HttpClientConfigurations.ConfigurationKey).Get<HttpClientConfigurations>();
            services.AddHttpClient(HttpClientName.DefaultHttpClientWithProxy).ConfigurePrimaryHttpMessageHandler(() => new SocketsHttpHandler
            {
                UseProxy = httpConfig.IsUseProxy,
                Proxy = new WebProxy(httpConfig.ProxyAddress, httpConfig.ByPassLocal)
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