namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Matches;

    public interface ISyncMatchService
    {
        Task<DateTime> GetMatchLastUpdatedTimeAsync();

        Task<long> GetMaxMatchIdAsync();

        Task SyncMatchByLastUpdatedTimeAsync(List<MatchResponse> matches);

        Task SyncMatchDetailsAsync(List<MatchDetailResponse> matchDetails);

        Task SyncMatchLineupByIdsAsync(List<MatchLineupResponse> matchLineups);

        Task SyncMultipleMatchCommentaryAsync(List<MatchCommentary> commentaries);

        Task SyncMultipleMatchIncidentAsync(List<MatchIncident> incidents);
    }
}