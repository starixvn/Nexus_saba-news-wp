namespace SabaNews.Site.Extensions
{
    using Fanex.Caching;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Hangfire;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;

    public static class DI
    {
        public static void RegisterCoreDI(this IServiceCollection services)
        {
            services.AddSingleton<IBaseService, BaseService>();
            services.AddSingleton<IDynamicRepository, DynamicRepository>();
            services.AddSingleton<IBaseRepository, BaseRepository>();
            services.AddScoped<ILogger, Logger>();
            services.AddScoped<IBaseMailService, BaseMailService>();
            services.AddScoped<ISessionService, SessionService>();
        }

        public static void RegisterServices(this IServiceCollection services)
        {
            services.AddTransient<ISerializerService, SerializerService>();
            services.AddTransient<IAuthService, AuthService>();
            services.AddTransient<IPostService, PostService>();
            services.AddTransient<IPathService, PathService>();
            services.AddTransient<ICacheService, CacheService>();
            services.AddTransient<IRecurringJobManager, RecurringJobManager>();

            services.AddTransient<IFetchDataService, FetchDataService>();
            services.AddTransient<IRefreshCachingService, RefreshCachingService>();
            services.AddTransient<IDataControlService, DataControlService>();
            services.AddTransient<IBackgroundJobService, BackgroundJobService>();
        }
    }
}