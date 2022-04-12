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

        public async Task<LiveMatchDetail> GetMatchDetaiAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                using (var multi = await db.QueryMultipleAsync())
                {
                    var match = multi.Read<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        splitOn: "LeagueId, HomeTeamId, AwayTeamId");

                    var homeStatistic = await multi.ReadFirstAsync<TeamStatistic>();
                    var awayStatistic = await multi.ReadFirstAsync<TeamStatistic>();
                    var incidents = await multi.ReadAsync<MatchIncident>();
                    var lineupSumary = await multi.ReadFirstAsync<Lineup>();
                    var homeLineup = await multi.ReadAsync<LineupTeam>();
                    var awayLineup = await multi.ReadAsync<LineupTeam>();
                    return new LiveMatchDetail
                    {
                        MatchInfo = match.FirstOrDefault(),
                        HomeStatistic = homeStatistic,
                        AwayStatistic = awayStatistic,
                        Incidents = incidents,
                        Lineup = lineupSumary,
                        HomeLineup = homeLineup,
                        AwayLineup = awayLineup
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