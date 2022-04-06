namespace SabaNews.Livescore.SyncData.Services
{
    using System.Threading.Tasks;

    public interface ISyncPlayerService
    {
        Task SyncPlayerListByLastUpdatedTimeAsync();
    }
}