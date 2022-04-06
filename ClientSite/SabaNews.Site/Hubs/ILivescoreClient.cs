namespace SabaNews.Site.Hubs
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;

    public interface ILivescoreClient
    {
        Task UpdateLiveMatches(IList<Match> matches);

        Task UpdateLiveMatchStatistic(Statistic statistic, LineupDetail lineup, IEnumerable<MatchIncident> incidents);
    }
}