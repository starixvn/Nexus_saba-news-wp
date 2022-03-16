namespace SabaNews.Livescore.Provider.API.Helpers
{
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.Provider.API.Services.Interfaces;

    public static class ProviderApiExtensions
    {
        public static void RegisterDI(IServiceCollection services)
        {
            services.AddTransient<IFetchMatchService, FetchMatchService>();
        }
    }
}