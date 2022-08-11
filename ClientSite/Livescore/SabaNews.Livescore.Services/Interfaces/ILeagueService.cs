namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services.Criteria.League;

    public interface ILeagueService
    {
        Task<IList<League>> GetMajorLeagueAsync();
        Task<IList<League>> GetLeagueByIdsAsync(GetLeagueByIdsCriteria criteria);
        Task<League> GetLeagueByIdAsync(long leagueId);
        Task<IList<League>> GetLeaguesAsync();
        Task<IList<League>> GetAllLeaguesAsync();
        IList<League> SearchLeague(IList<League> leagues, string search, int isMajor);
        IList<League> SortLeague(IList<League> leagues, int typeSort);
        Task<IList<TopScorers>> GetTopScorersAsync(GetTopScorersCriteria topScorersCriteria);
        Task<CommandResult> UpdateLeaguesExtraAsync(League league);
        Task<CommandResult> DeleteLeaguesExtraAsync(long leagueId);
        Task<CommandResult> InsertLeaguesExtraAsync(long leagueId);
        Task<CommandResult> UpdateLeaguesAsync(UpdateLeagueCriteria updateLeague);
        Task<IList<SeasonStandings>> GetLeagueSeasonStanding(GetLeagueSeasonStandingCriteria criteria);
    }
}