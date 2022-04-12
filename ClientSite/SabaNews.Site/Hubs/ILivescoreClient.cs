namespace SabaNews.Site.Hubs
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;

    public interface ILivescoreClient
    {
        Task UpdateLiveMatches(IList<Match> matches);

        Task UpdateLiveMatchDetail(MatchDetail detail);

        Task SendAsync(string data);

        Task UpdateMatchDetail(MatchDetail matchDetail);
    }
}