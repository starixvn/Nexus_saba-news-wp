namespace SabaNews.Site.Extensions
{
    using System;
    using System.Net;
    using System.Net.Http;
    using System.Security.Claims;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Hangfire;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Authentication.Google;
    using Microsoft.AspNetCore.Authentication.OAuth;
    using Microsoft.AspNetCore.Builder;
    using Microsoft.AspNetCore.Http;
    using Microsoft.Extensions.Configuration;
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
            services.AddTransient<ICommentService, CommentService>();
            services.AddTransient<IExternalAuthService, ExternalAuthService>();
        }

        public static void RegisterAuthentication(this IServiceCollection services, IConfiguration configuration)
        {
            const int ExpireMinutes = 10;

            services.AddAuthentication(options =>
            {
                options.DefaultScheme = CookieAuthenticationDefaults.AuthenticationScheme;
                options.DefaultChallengeScheme = GoogleDefaults.AuthenticationScheme;
            })
                .AddCookie(options =>
                {
                    options.Cookie.IsEssential = true;
                    options.ExpireTimeSpan = TimeSpan.FromMinutes(ExpireMinutes);
                })
                .AddGoogle(options =>
                {
                    options.SignInScheme = CookieAuthenticationDefaults.AuthenticationScheme;
                    options.ClientId = configuration.GetValue<string>("Authentication:Google:ClientId");
                    options.ClientSecret = configuration.GetValue<string>("Authentication:Google:ClientSecret");
                    options.Backchannel = GetDefaultHttpClient(configuration);
                    options.CorrelationCookie = new CookieBuilder
                    {
                        Expiration = TimeSpan.FromDays(1),
                        Name = "Sabasports.Google.Cookie"
                    };
                }).AddFacebook(options =>
                {
                    options.SignInScheme = CookieAuthenticationDefaults.AuthenticationScheme;
                    options.ClientId = configuration.GetValue<string>("Authentication:Facebook:ClientId");
                    options.ClientSecret = configuration.GetValue<string>("Authentication:Facebook:ClientSecret");
                    options.Backchannel = GetDefaultHttpClient(configuration);

                    options.CorrelationCookie = new CookieBuilder
                    {
                        Expiration = TimeSpan.FromDays(1),
                        Name = "Sabasports.Facebook.Cookie"
                    };
                    options.Fields.Add("picture");
                    options.Events = new OAuthEvents
                    {
                        OnCreatingTicket = context =>
                        {
                            var identity = (ClaimsIdentity)context.Principal.Identity;
                            var profileImg = context.User.GetProperty("picture").GetProperty("data").GetProperty("url").ToString();
                            identity.AddClaim(new Claim(ClaimTypes.Thumbprint, profileImg));
                            return Task.CompletedTask;
                        }
                    };
                });
        }

        public static void RegisterCookiePolicy(this IServiceCollection services)
        {
            services.Configure<CookiePolicyOptions>(options =>
            {
                options.MinimumSameSitePolicy = SameSiteMode.Unspecified;
                options.OnAppendCookie = cookieContext =>
                    CheckSameSite(cookieContext.Context, cookieContext.CookieOptions);
                options.OnDeleteCookie = cookieContext =>
                    CheckSameSite(cookieContext.Context, cookieContext.CookieOptions);
            });
        }

        private static void CheckSameSite(HttpContext httpContext, CookieOptions options)
        {
            if (options.SameSite == SameSiteMode.None)
            {
                var userAgent = httpContext.Request.Headers["User-Agent"].ToString();
                if (DisallowsSameSiteNone(userAgent))
                {
                    options.SameSite = SameSiteMode.Unspecified;
                }
            }
        }

        private static bool DisallowsSameSiteNone(string userAgent)
        {
            if (string.IsNullOrWhiteSpace(userAgent))
            {
                return false;
            }

            if (userAgent.Contains("CPU iPhone OS 12") ||
                userAgent.Contains("iPad; CPU OS 12"))
            {
                return true;
            }

            if (userAgent.Contains("Macintosh; Intel Mac OS X 10_14") &&
                userAgent.Contains("Version/") && userAgent.Contains("Safari"))
            {
                return true;
            }

            if (userAgent.Contains("Chrome/5") || userAgent.Contains("Chrome/6"))
            {
                return true;
            }

            return false;
        }

        private static HttpClient GetDefaultHttpClient(IConfiguration configuration)
        {
            var isUseProxy = bool.Parse(configuration.GetSection("ProxySettings:IsUseProxy").Value);
            var proxyAddress = configuration.GetSection("ProxySettings:ProxyAddress").Value;
            var bypassOnLocal = bool.Parse(configuration.GetSection("ProxySettings:BypassProxyOnLocal").Value);

            HttpClient httpClient;

            if (isUseProxy)
            {
                var proxy = new WebProxy(proxyAddress, bypassOnLocal);
                var socketsHttpHandler = new SocketsHttpHandler
                {
                    UseProxy = isUseProxy,
                    Proxy = proxy,
                };

                httpClient = new HttpClient(handler: socketsHttpHandler);
            }
            else
            {
                httpClient = new HttpClient();
            }

            return httpClient;
        }
    }
}