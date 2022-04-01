namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Extensions;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Repositories;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Services;

    public class MatchService : IMatchService
    {
        private readonly IMatchRepository matchRepository;
        private readonly IBaseService baseService;

        public MatchService(IMatchRepository matchRepository, IBaseService baseService)
        {
            this.matchRepository = matchRepository;
            this.baseService = baseService;
        }

        public async Task<IEnumerable<Match>> GetMatchByEventDateAsync(GetMatchByEventDateCriteria criteria)
        {
            IEnumerable<Match> matches = await matchRepository.GetMatchByEventDateAsync(criteria);
            foreach(var match in matches)
            {
                match.StartTime = match.StartTime.ConvertFromUTC();
                match.EndTime = match.EndTime.ConvertFromUTC();
            }

            return matches;
        }


        public async Task<MathGeneralInfo> GetMatchDetailAsync(GetMatchDetailCriteria criteria)
        {
            var result = await baseService.GetDataEntityAsync<MathGeneralInfo>(criteria);

            return result;
        }


        public async Task<LineupDetail> GetLineupAsync(GetMatchLineupCriteria criteria)
        {
            var (generalInfo, home, away) = await baseService.FetchMultipleAsync<Lineup, LineupTeam, LineupTeam>(criteria);
            var data = new LineupDetail
            {
                GeneralInfo = generalInfo.FirstOrDefault(),
                Home = home.ToList(),
                Away = away.ToList()
            };
            return data;
        }

        public async Task<MatchDetailPostMatch> GetMatchDetailPostMatch(GetMatchDetailPostMatchCriteria criteria)
        {
            var (incidents, homeStatictis, awayStatistic) = await baseService.FetchMultipleAsync<MatchIncidentEvent, TeamStatistic, TeamStatistic>(criteria);
            var data = new MatchDetailPostMatch
            {
                Incidents = incidents,
                HomeStatistic = homeStatictis.FirstOrDefault(),
                AwayStatistic = awayStatistic.FirstOrDefault()
            };
            return data;
        }
    }
}