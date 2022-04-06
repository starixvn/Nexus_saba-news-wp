namespace SabaNews.Livescore.Services
{
    using Microsoft.Extensions.DependencyInjection;
    using SabaNews.Livescore.Repositories;
    using SabaNews.Livescore.Services.Interfaces;

    public static class ServiceDIExtensions
    {
        public static void ResolveLivescoreServiceDI(this IServiceCollection services)
        {
            services.AddScoped<IMatchRepository, MatchRepository>();

            services.AddScoped<ILeagueService, LeagueService>();
            services.AddScoped<IMatchService, MatchService>();
            services.AddScoped<IPushSignalService, PushSignalService>();
        }
    }
}