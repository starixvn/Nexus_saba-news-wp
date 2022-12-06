namespace SabaNews.Core.Extensions
{
    using Fanex.Caching;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Microsoft.AspNetCore.Http;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;

    public static class CoreDI
    {
        public static void RegisterService(IServiceCollection services)
        {
            services.AddSingleton<IBaseService, BaseService>();
            services.AddSingleton<IDynamicRepository, DynamicRepository>();
            services.AddSingleton<IBaseRepository, BaseRepository>();
            services.AddSingleton<IHttpContextAccessor, HttpContextAccessor>();
            services.AddSingleton<ICacheService, CacheService>();
            services.AddSingleton<ICacheWrapperService, CacheWrapperService>();
            services.AddSingleton<ILogger, Logger>();
            services.AddSingleton<ILogServiceProvider, LogServiceProvider>();
            services.AddScoped<IBaseMailService, BaseMailService>();
            services.AddScoped<ISessionService, SessionService>();
        }
    }
}