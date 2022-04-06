namespace SabaNews.Livescore.SyncData.Services
{
    using System.Threading.Tasks;

    public interface ISyncSeasonService
    {
        Task SyncSeasonListByLastUpdatedTimeAsync();

        Task SyncStageListByLastUpdatedTimeAsync();

        Task SyncSeasonStandingsByLastUpdatedTimeAsync();

        Task SyncSeasonTeamHonorByLastUpdatedTimeAsync();

        Task SyncSeasonPlayerHonorByLastUpdatedTimeAsync();
    }
}