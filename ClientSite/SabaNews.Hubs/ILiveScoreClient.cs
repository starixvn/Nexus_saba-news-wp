namespace SabaNews.Hubs
{
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface ILiveScoreClient
    {
        Task UpdateLiveSoccer();

        Task UpdateLiveSoccerCommentary();
    }
}