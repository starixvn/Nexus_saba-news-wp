namespace SabaNews.Livescore.SyncData.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Synchronous;

    public interface ISyncMatchService
    {
        Task SyncMatchListAsync(SyncMatchParams syncMatchParams);
    }
}