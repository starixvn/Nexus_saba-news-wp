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

        Task SyncMatchByLastUpdatedTimeAsync(List<MatchResponse> matches, bool skipUpdatedAt = false);

        Task SyncMatchStatisticAsync(List<MatchDetailResponse> matchDetails);

        Task SyncMatchLineupByIdsAsync(List<MatchLineupResponse> matchLineups);

        Task SyncMultipleMatchCommentaryAsync(List<MatchCommentary> commentaries);

        Task SyncMatchIncidentAsync(long matchId, List<MatchIncident> incidents);

        Task SyncDeletedMatchesAsync(List<DeletedMatch> delMatches);

        Task SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(DateTime? startTime, DateTime? endTime, DateTime? lastUpdatedTime);

        Task<DateTime> GetMatchLastUpdatedTimeInDateRangeAsync(DateTime? startTime, DateTime? endTime, bool isExcludeLiveMatch = false);
        Task SyncIncidentByUpdatedTimeAsync();
        Task SyncIncidents(long[] matchIds);
    }
}