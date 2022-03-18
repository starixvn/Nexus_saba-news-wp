namespace SabaNews.Livescore.Provider.API.Helpers
{
    using System.Collections.Generic;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Microsoft.AspNetCore.Http;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.Provider.API.Services.Interfaces;
    using SabaNews.Services;

    public static class ProviderApiHelpers
    {
        public static void RegisterDI(IServiceCollection services)
        {
            services.AddTransient<IFetchMatchService, FetchMatchService>();
        }

        public static void RegisterCoreDI(this IServiceCollection services)
        {
            services.AddSingleton<IBaseService, BaseService>();
            services.AddSingleton<IDynamicRepository, DynamicRepository>();
            services.AddSingleton<IBaseRepository, BaseRepository>();
            services.AddSingleton<IHttpContextAccessor, HttpContextAccessor>();
            services.AddScoped<ILogger, Logger>();
            services.AddSingleton<ILogServiceProvider, LogServiceProvider>();
        }

        public static Dictionary<string, object> InitParams(bool isIncludeSportId = true)
        {
            var param = new Dictionary<string, object>();
            if (isIncludeSportId)
            {
                param.Add("sportId", CommonConstants.SoccerSportId);
            }
            return param;
        }
    }
}