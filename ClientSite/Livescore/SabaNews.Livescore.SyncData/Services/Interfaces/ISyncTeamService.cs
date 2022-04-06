namespace SabaNews.Livescore.SyncData.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Team;

    public interface ISyncTeamService
    {
        Task SyncTeamListByLastUpdatedTimeAsync();

        Task SyncTeamListGreaterThanTeamIdAsync();

        Task SyncHeadToHeadAsync(List<HeadToHeadResponse> h2hResponseList);
    }
}