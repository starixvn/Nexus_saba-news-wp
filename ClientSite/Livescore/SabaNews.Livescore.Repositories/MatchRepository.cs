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
                var matches = await db.QueryAsync<Match, League, Team, Team, Match>(
                        (match, league, home, away) =>
                        {
                            match.League = league;
                            match.HomeTeam = home;
                            match.AwayTeam = away;

                            return match;
                        },
                        splitOn: "LeagueId, HomeTeamId, AwayTeamId",
                        parameterSet: criteria);
                return matches.ToList();
            }
        }

        public async Task<MatchDetail> GetMatchDetaiAsync(ICriteria criteria)
        {
            using (IObjectDb db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                using (var multi = await db.QueryMultipleAsync())
                {
                    var match = multi.Read<Match, League, Team, Team, Match>((match, league, home, away) =>
                   {
                       match.League = league;
                       match.HomeTeam = home;
                       match.AwayTeam = away;
                       return match;
                   }, splitOn: "LeagueId, HomeTeamId, AwayTeamId");

                    var statistic = await multi.ReadAsync<Statistic>();
                    var lineup = await multi.ReadAsync<LineupDetail>();
                    var incidents = await multi.ReadAsync<MatchIncident>();

                    return new MatchDetail
                    {
                        GeneralInfo = match.FirstOrDefault(),
                        Statistic = statistic.FirstOrDefault(),
                        Lineup = lineup.FirstOrDefault(),
                        Incidents = incidents
                    };
                }
            }
        }
    }
}