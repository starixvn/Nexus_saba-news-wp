namespace SabaNews.Site.Extensions
{
    using Fanex.Caching;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Hangfire;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Identity;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using SabaNews.Models.Auth;
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

        public static void RegisterAuthentication(this IServiceCollection services, IConfiguration configuration)
        {
            services.AddIdentity<GoogleUser, IdentityRole>(options =>
            {
                options.User.RequireUniqueEmail = false;
            }).AddUserStore<ExternalUserService>()
                .AddRoleStore<ExternalUserService>()
                .AddDefaultTokenProviders();

            services.AddAuthentication(options =>
            {
                options.DefaultChallengeScheme = CookieAuthenticationDefaults.AuthenticationScheme;
                options.DefaultSignInScheme = CookieAuthenticationDefaults.AuthenticationScheme;
                options.DefaultAuthenticateScheme = CookieAuthenticationDefaults.AuthenticationScheme;
            }).AddGoogle(options =>
            {
                options.ClientId = configuration.GetValue<string>("Authentication:Google:ClientId");
                options.ClientSecret = configuration.GetValue<string>("Authentication:Google:ClientSecret");
                options.CallbackPath = "/ExternalAuthentication/GoogleResponse";
                options.SaveTokens = true;
                options.CorrelationCookie.Name = ".SabaSports.Google.Cookies";
                options.CorrelationCookie.SameSite = SameSiteMode.Lax;
            });
        }
    }
}