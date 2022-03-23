namespace SabaNews.Livescore.Hubs
{
    using System.Threading.Tasks;

    public interface ILivescoreClient
    {
        Task UpdateLiveSoccer();

        Task UpdateLiveSoccerCommentary();
    }
}