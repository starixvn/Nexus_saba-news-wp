namespace SabaNews.Livescore.SyncData.Services
{
    using System.Threading.Tasks;

    public interface ISyncLeagueService
    {
        Task SyncLeagueListByLastUpdatedTimeAsync();

        Task SyncLeagueListGreaterThanLeagueIdAsync();

        Task SyncLeagueSeasonByIdsAsync(long?[] leagueIds);
    }
}