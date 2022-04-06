namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.League;

    public interface IFetchLeagueService
    {
        Task<List<LeagueListResponse>> FetchLeagueListAsync(FetchLeagueListParams leagueParams);
    }
}