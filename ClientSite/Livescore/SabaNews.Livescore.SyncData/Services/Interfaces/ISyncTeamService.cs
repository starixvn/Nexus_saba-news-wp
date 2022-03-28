namespace SabaNews.Livescore.SyncData.Services
{
    using System.Threading.Tasks;

    public interface ISyncTeamService
    {
        Task SyncTeamListByLastUpdatedTimeAsync();

        Task SyncTeamListGreaterThanTeamIdAsync();
    }
}