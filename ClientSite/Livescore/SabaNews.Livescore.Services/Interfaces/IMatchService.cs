namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services.Criteria.Match;

    public interface IMatchService
    {
        Task<IList<Match>> GetMatchByDateAsync(GetMatchByDateCriteria criteria);

        Task<IList<Match>> GetLiveMatchAsync();

        Task<MathGeneralInfo> GetMatchDetailAsync(GetMatchDetailCriteria criteria);

        Task<LineupDetail> GetLineupAsync(GetMatchLineupCriteria criteria);

        Task<MatchDetailPostMatch> GetMatchDetailPostMatch(GetMatchDetailPostMatchCriteria criteria);
    }
}