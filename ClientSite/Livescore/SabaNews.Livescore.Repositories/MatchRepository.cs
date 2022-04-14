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

        public async Task<(League, IList<Match>)> GetMatchByStageAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                using (IMultiResultReader multi = await db.QueryMultipleAsync())
                {
                    var league = multi.Read<League, Season, SeasonStage, League>(
                        (league, season, stage) =>
                        {
                            league.Season = season;
                            league.Stage = stage;

                            return league;
                        },
                        splitOn: "SeasonId, StageId").FirstOrDefault();

                    var matches = multi.Read<Match, Team, Team, Match>(
                        (match, home, away) =>
                        {
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        splitOn: "HomeTeamId, AwayTeamId");

                    return (league, matches.ToList());
                }
            }
        }

        public async Task<HeadToHeadDetail> GetHeadToHeadAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                using (var multi = await db.QueryMultipleAsync(criteria))
                {
                    var common = multi.Read<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        "LeagueId, HomeTeamId, AwayTeamId");

                    var home = multi.Read<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        "LeagueId, HomeTeamId, AwayTeamId");

                    var away = multi.Read<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        "LeagueId, HomeTeamId, AwayTeamId");

                    return new HeadToHeadDetail
                    {
                        Common = common,
                        Home = home,
                        Away = away
                    };
                    
                }
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
                        Common = await multi.ReadAsync<Match>(),
                        Home = await multi.ReadAsync<Match>(),
                        Away = await multi.ReadAsync<Match>()
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