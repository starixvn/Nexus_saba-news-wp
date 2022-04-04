namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.SyncData.Models.Matches;

    public interface ISyncMatchService
    {
        Task<DateTime> GetMatchLastUpdatedTime();

        Task SyncMatchByLastUpdatedTimeAsync(List<MatchResponse> matches);

        Task SyncMatchDetailByIdsAsync(List<MatchDetailResponse> matchDetails);

        Task SyncMatchLineupByIdsAsync(List<MatchLineupResponse> matchLineups);

        Task SyncMultipleMatchCommentaryAsync(List<MatchCommentary> commentaries);

        Task SyncMultipleMatchIncidentAsync(List<MatchIncident> incidents);

        Task InsertTemporaryMatch(long[] matchIds);

        Task<IEnumerable<TemporaryMatch>> GetTemporaryMatchToFetchInfo(int rowLimit);

        Task RemoveMatchesFromTemporaryTable(long[] matchIds);
    }
}