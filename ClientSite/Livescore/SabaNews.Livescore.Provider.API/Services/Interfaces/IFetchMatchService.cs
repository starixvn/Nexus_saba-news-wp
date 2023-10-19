namespace SabaNews.Livescore.Provider.API.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;

    public interface IFetchMatchService
    {
        Task<List<MatchResponse>> FetchMatchListAsync(SyncMatchParams paramModel);

        Task<List<MatchDetailResponse>> FetchLiveMatchListAsync(SyncMatchParams paramModel);

        Task<List<MatchDetailResponse>> FetchMatchDetailAsync(SyncMatchParams paramModel);

        Task<List<MatchLineupResponse>> FetchMatchLineupAsync(long[] matchIds);

        Task<List<MatchCommentary>> FetchMatchCommentaryAsync(long matchId);

        Task<List<MatchIncident>> FetchMatchIncidentAsync(short sportId, long matchId);

        Task<List<DeletedMatch>> FetchDeletedMatchAsync();

        Task<List<long>> FetchMatchIncidentUpdateTimeAsync(short sportId, DateTime? lastUpdatedTime);

        Task<IDictionary<string, MatchIncidentResponse>> FetchMultipleMatchIncidentsAsync(short sportId, long[] MatchIds);
    }
}