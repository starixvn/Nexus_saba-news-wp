namespace SabaNews.Services.Interfaces
{
    using SabaNews.Criteria.Livescore;
    using SabaNews.Models.LiveScore;
    using System.Threading.Tasks;

    public interface ILiveScoreService
    {
        Task<SoccerMathGeneralInfo> GetMatchDetailAsync(GetMatchDetailCriteria criteria);

        Task<SoccerMatchLineupDetail> GetLineupAsync(GetMatchLineupCriteria criteria);

        Task<SoccerMatchDetailPostMatch> GetMatchDetailPostMatch(GetMatchDetailPostMatchCriteria criteria);
    }
}
