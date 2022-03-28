namespace SabaNews.Livescore.SyncData.Services
{
    using System.Threading.Tasks;

    public interface ISyncMatchService
    {
        Task SyncAllMatchAsync();

        Task SyncMatchDetailByIdsAsync(long[] matchIds);

        Task SyncMatchLineupByIdsAsync(long[] matchIds);

        Task SyncMatchCommentaryAsync(long matchId);

        Task SyncMatchIncidentAsync(int sportId, long matchId);
    }
}