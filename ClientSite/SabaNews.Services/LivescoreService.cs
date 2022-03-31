namespace SabaNews.Services
{
    using SabaNews.Criteria.Livescore;
    using SabaNews.Models.LiveScore;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Linq;
    using System.Threading.Tasks;

    public class LivescoreService : ILiveScoreService
    {
        private readonly IBaseService baseService;

        public LivescoreService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<SoccerMathGeneralInfo> GetMatchDetailAsync(GetMatchDetailCriteria criteria)
        {
            var result = await baseService.GetDataEntityAsync<SoccerMathGeneralInfo>(criteria);
            
            return result;
        }


        public async Task<SoccerMatchLineupDetail> GetLineupAsync(GetMatchLineupCriteria criteria)
        {
            var (generalInfo, home, away) = await baseService.FetchMultipleAsync<SoccerMatchLineup, SoccerMatchLineupTeam, SoccerMatchLineupTeam>(criteria);
            var data = new SoccerMatchLineupDetail
            {
                GeneralInfo = generalInfo.FirstOrDefault(),
                Home = home.ToList(),
                Away = away.ToList()
            };
            return data;
        }

        public async Task<SoccerMatchDetailPostMatch> GetMatchDetailPostMatch(GetMatchDetailPostMatchCriteria criteria)
        {
            var (incidents, homeStatictis, awayStatistic) = await baseService.FetchMultipleAsync<SoccerMatchCommentaryEvent, SoccerMatchTeamStatistic, SoccerMatchTeamStatistic>(criteria);
            var data = new SoccerMatchDetailPostMatch
            {
                Incidents = incidents,
                HomeStatistic = homeStatictis.FirstOrDefault(),
                AwayStatistic = awayStatistic.FirstOrDefault()
            };
            return data;
        }
    }
}