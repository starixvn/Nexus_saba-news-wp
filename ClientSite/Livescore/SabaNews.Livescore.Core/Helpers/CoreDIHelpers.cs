namespace SabaNews.Livescore.Core.Helpers
{
    using System.Net;
    using System.Net.Http;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Constants;
    using SabaNews.Livescore.Core.Models;
    using SabaNews.Livescore.Core.Services;
    using SabaNews.Livescore.Core.Services.Interfaces;
    using SabaNews.Services;

    public static class CoreDIHelpers
    {
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