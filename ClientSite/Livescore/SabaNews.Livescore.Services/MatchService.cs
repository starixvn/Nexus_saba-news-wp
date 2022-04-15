namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Repositories;
    using SabaNews.Livescore.Services.Criteria.Match;

    public class MatchService : IMatchService
    {
        private readonly IMatchRepository matchRepository;
        private readonly IBaseService baseService;

        public MatchService(IMatchRepository matchRepository, IBaseService baseService)
        {
            this.matchRepository = matchRepository;
            this.baseService = baseService;
        }

        public async Task<IList<Match>> GetMatchByDateAsync(GetMatchByDateCriteria criteria)
        {
            return await matchRepository.GetMatchByDateAsync(criteria);
        }

        public async Task<IList<Match>> GetLiveMatchAsync()
        {
            var criteria = new GetLiveMatchCriteria();
            return await matchRepository.GetMatchByDateAsync(criteria);
        }

        public async Task<(League, IList<Match>)> GetMatchBySeasonAsync(GetMatchBySeasonCriteria criteria)
        {
            return await matchRepository.GetMatchBySeasonAsync(criteria);
        }

        public async Task<Match> GetMatchByIdAsync(GetMatchByIdCriteria criteria)
        {
            var result = await matchRepository.GetMatchByIdAsync(criteria);

            return result;
        }

        public async Task<MatchDetail> GetMatchDetailAsync(GetMatchDetailCriteria criteria)
        {
            var result = await matchRepository.GetMatchDetaiAsync(criteria);

            return result;
        }

        public async Task<IEnumerable<MatchIncident>> GetMatchIncidentAsync(GetMatchIncidentCriteria criteria)
        {
            var result = await baseService.GetDataAsync<MatchIncident>(criteria);

            return result;
        }

        public async Task<Statistic> GetMatchStatisticAsync(GetMatchStatisticCriteria criteria)
        {
            var (homeStatictis, awayStatistic) = await baseService.FetchMultipleAsync<TeamStatistic, TeamStatistic>(criteria);
            var data = new Statistic
            {
                HomeStatistic = homeStatictis.FirstOrDefault(),
                AwayStatistic = awayStatistic.FirstOrDefault()
            };
            return data;
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

        public async Task<HeadToHeadDetail> GetHeadToHeadAsync(GetHeadToHeadCriteria criteria)
        {
            var data = await matchRepository.GetHeadToHeadAsync(criteria);
            return data;
        }
    }
}