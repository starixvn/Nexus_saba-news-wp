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
        private const string splitOnMatch = "LeagueId, HomeTeamId, AwayTeamId";

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

        public async Task<HeadToHeadDetail> GetHeadToHeadAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                using (var multi = await db.QueryMultipleAsync(criteria))
                {
                    var matches = multi.Read<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        splitOnMatch);

                    var team = await multi.ReadFirstOrDefaultAsync<H2HTeam>();
                    var common = new List<Match>();
                    var home = new List<Match>();
                    var away = new List<Match>();
                    if (matches.Any() && team != null) {
                        const byte TotalMatches = 5;
                        common = matches.Where(item => (item.HomeTeam.TeamId == team.HomeId && item.AwayTeam.TeamId == team.AwayId)
                                    || (item.HomeTeam.TeamId == team.AwayId && item.AwayTeam.TeamId == team.HomeId))
                            .Skip(0).Take(TotalMatches).ToList();

                        home = matches.Where(item => item.HomeTeam.TeamId == team.HomeId || item.AwayTeam.TeamId == team.HomeId)
                            .Skip(0).Take(TotalMatches).ToList();

                        away = matches.Where(item => item.HomeTeam.TeamId == team.AwayId || item.AwayTeam.TeamId == team.AwayId)
                            .Skip(0).Take(TotalMatches).ToList();
                    }

                    return new HeadToHeadDetail
                    {
                        Common = common,
                        Home = home,
                        Away = away
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
                        splitOn: splitOnMatch,
                        parameterSet: criteria);
                return matches;
            }
        }

        public async Task<IList<Match>> GetMatchByIdsAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                var matches = await db.QueryAsync<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        splitOn: splitOnMatch,
                        parameterSet: criteria);
                return matches.ToList();
            }
        }
    }
}