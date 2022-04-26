namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
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
            (var leagues, var floatMatches) = await baseService.FetchMultipleAsync<League, FloatMatchModel>(criteria);
            var matches = new List<Match>();
            foreach(FloatMatchModel match in floatMatches)
            {
                var stage = match.StageId.HasValue
                    ? new SeasonStage
                    {
                        StageId = match.StageId.Value,
                        StageNameEN = match.StageNameEN,
                        Mode = match.StageMode,
                        GroupCount = match.GroupCount,
                        RoundCount = match.RoundCount,
                        OrderIndex = match.OrderIndex
                    }
                    : null;
                matches.Add(new Match
                {
                    MatchId = match.MatchId,
                    StartTime = match.StartTime,
                    EndTime = match.EndTime,
                    Status = match.Status,
                    GroupNum = match.GroupNum,
                    Round = match.Round,
                    HomeTeamHalfScore = match.HomeTeamHalfScore,
                    AwayTeamHalfScore = match.AwayTeamHalfScore,
                    HomeTeamTotalScore = match.HomeTeamTotalScore,
                    AwayTeamTotalScore = match.AwayTeamTotalScore,
                    HomeTeamAggregateScore = match.HomeTeamAggregateScore,
                    AwayTeamAggregateScore = match.AwayTeamAggregateScore,
                    HomeTeamExtraScore = match.HomeTeamExtraScore,
                    AwayTeamExtraScore = match.AwayTeamExtraScore,
                    HomeTeamPenaltyScore = match.HomeTeamPenaltyScore,
                    AwayTeamPenaltyScore = match.AwayTeamPenaltyScore,
                    HomeTeam = new Team
                    {
                        TeamId = match.HomeTeamId,
                        TeamNameEN = match.HomeTeamNameEN,
                        TeamFullNameEN = match.HomeTeamFullNameEN,
                        Icon = match.HomeTeamIcon
                    },
                    AwayTeam = new Team
                    {
                        TeamId = match.AwayTeamId,
                        TeamNameEN = match.AwayTeamNameEN,
                        TeamFullNameEN = match.AwayTeamFullNameEN,
                        Icon = match.AwayTeamIcon
                    },
                    Stage = stage
                });
            }

            return (leagues.FirstOrDefault(), matches);
        }

        public async Task<IList<SeasonStandings>> GetSeasonStandingsAsync(GetSeasonStandingsCriteria criteria)
        {
            (var standings, var floatMatches) = await baseService.FetchMultipleAsync<SeasonStandings, FloatMatchModel>(criteria);
            foreach(var item in standings)
            {
                item.Matches = floatMatches.Where(match => match.StageId == item.StageId
                    && (match.HomeTeamId == item.TeamId || match.AwayTeamId == item.TeamId)).ToList();
            }

            return standings.ToList();
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
            return await matchRepository.GetHeadToHeadAsync(criteria);
        }

        public async Task<IList<Match>> GetMatchByIdsAsync(GetMatchByIdsCriteria criteria)
        {
            return await matchRepository.GetMatchByIdsAsync(criteria);
        }
    }
}