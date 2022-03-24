namespace SabaNews.Livescore.SyncData.Services.Interfaces
{
    using System.Threading.Tasks;

    public interface ISyncMatchService
    {
        Task SyncAllMatchAsync();

        Task SyncMatchDetailAsync();
    }
}