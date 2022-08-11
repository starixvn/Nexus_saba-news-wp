namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
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

        Task<CommandResult> UpdateLeaguesExtraAsync(League league);
        Task<CommandResult> DeleteLeaguesExtraAsync(long leagueId);
        Task<CommandResult> InsertLeaguesExtraAsync(long leagueId);
        Task<CommandResult> UpdateLeaguesAsync(UpdateLeagueCriteria updateLeague);
        Task<IList<SeasonStandings>> GetLeagueSeasonStanding(GetLeagueSeasonStandingCriteria criteria);
        Task<IList<Team>> GetAllTeamAsync();
        Task<IList<Player>> GetPlayerByIdTeamAsync(long teamId);
        Task<IList<TopScorers>> GetTop10ScorersAsync(GetTop10ScorersCriteria top10ScorersCriteria);
        Task<IList<TopScorers>> GetAllTopScorersAsync();
        Task<IList<TopScorers>> GetTopScorersByRowNumAsync(int rowNum);
        Task<CommandResult> InsertTopScorerAsync(TopScorers topScorers);
        Task<CommandResult> UpdateTopScorerAsync(TopScorers topScorers);
    }
}