namespace SabaNews.Hubs
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Models.LiveScore;

    public interface ILiveScoreClient
    {
        Task UpdateLiveSoccer(IList<SoccerMatch> soccerMatches);

        Task UpdateLiveSoccerCommentary(IList<SoccerMatchCommentary> commentaries);
    }
}