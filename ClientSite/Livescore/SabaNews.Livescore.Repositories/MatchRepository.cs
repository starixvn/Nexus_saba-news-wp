namespace SabaNews.Livescore.Repositories
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Fanex.Data;
    using Fanex.Data.Repository;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;

    public class MatchRepository : IMatchRepository
    {
        public async Task<IList<Match>> GetMatchByDateAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                var matches = await db.QueryAsync<Match, League, Season, SeasonStage, Team, Team, Match>(
                        (match, league, season, stage, home, away) =>
                        {
                            league.Season = season;
                            league.Stage = stage;

                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        splitOn: "LeagueId, SeasonId, StageId, HomeTeamId, AwayTeamId",
                        parameterSet: criteria);
                return matches.ToList();
            }
        }

        public async Task<MatchDetail> GetMatchDetaiAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                using (var multi = await db.QueryMultipleAsync(criteria))
                {
                    var match = multi.Read<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        "LeagueId, HomeTeamId, AwayTeamId");

                    var incidents = await multi.ReadAsync<MatchIncident>();
                    var homeStatistic = await multi.ReadAsync<TeamStatistic>();
                    var awayStatistic = await multi.ReadAsync<TeamStatistic>();
                    var lineupSumary = await multi.ReadAsync<Lineup>();
                    var homeLineup = await multi.ReadAsync<LineupTeam>();
                    var awayLineup = await multi.ReadAsync<LineupTeam>();
                    var headToHead = new HeadToHeadDetail
                    {
                        Common = await multi.ReadAsync<MatchHeadToHead>(),
                        Home = await multi.ReadAsync<MatchHeadToHead>(),
                        Away = await multi.ReadAsync<MatchHeadToHead>()
                    };
                    return new MatchDetail
                    {
                        GeneralInfo = match.FirstOrDefault(),
                        Statistic = new Statistic
                        {
                            HomeStatistic = homeStatistic.FirstOrDefault(),
                            AwayStatistic = awayStatistic.FirstOrDefault()
                        },
                        Incidents = incidents,
                        Lineup = new LineupDetail
                        {
                            GeneralInfo = lineupSumary.FirstOrDefault(),
                            Home = homeLineup,
                            Away = awayLineup
                        },
                        HeadToHead = headToHead
                    };
                }
            }
        }
        public async Task<Match> GetMatchByIdAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                var matches = await db.QueryEntityAsync<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        splitOn: "LeagueId, HomeTeamId, AwayTeamId",
                        parameterSet: criteria);
                return matches;
            }
        }
    }
}