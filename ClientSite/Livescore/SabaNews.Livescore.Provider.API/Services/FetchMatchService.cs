namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.Matches;

    public class FetchMatchService : IFetchMatchService
    {
        private readonly IHttpClientService httpClient;

        public FetchMatchService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<List<MatchResponse>> FetchMatchListAsync(SyncMatchParams paramModel)
        {
            var param = new Dictionary<string, object>
            {
                { "sportId", paramModel.SportId },
                { "matchId", paramModel.MatchIds.Any() ? paramModel.MatchIds : null },
                { "leagueId", paramModel.LeagueId },
                { "timeGte", paramModel.LastUpdatedTime.HasValue ? paramModel.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "startTime", paramModel.StartTime.HasValue ? paramModel.StartTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "endTime", paramModel.EndTime.HasValue ? paramModel.EndTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null }
            };

            if (paramModel.RowLimit is not null)
            {
                param.Add("rowLimit", paramModel.RowLimit);
            }

            var matches = await httpClient.ExecutePostAsync<List<MatchResponse>>(ApiPath.GetMatchListApiPath, param);

            return matches;
        }

        public async Task<List<MatchDetailResponse>> FetchMatchDetailAsync(SyncMatchParams paramModel)
        {
            var param = new Dictionary<string, object>
            {
                { "sportId", paramModel.SportId },
                { "matchId", paramModel.MatchIds.Any() ? paramModel.MatchIds : null },
                { "timeGte", paramModel.LastUpdatedTime.HasValue ? paramModel.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "rowLimit", paramModel.RowLimit }
            };

            var matcheDetails = await httpClient.ExecutePostAsync<List<MatchDetailResponse>>(ApiPath.GetMatchDetailApiPath, param);

            return matcheDetails;
        }

        public async Task<List<MatchLineupResponse>> FetchMatchLineupAsync(long[] matchIds)
        {
            var param = new Dictionary<string, object>
            {
                { "matchId", matchIds.Any() ? matchIds : null },
            };

            var matcheDetails = await httpClient.ExecutePostAsync<List<MatchLineupResponse>>(ApiPath.GetMatchLineupApiPath, param);

            return matcheDetails;
        }

        private static class ApiPath
        {
            internal const string GetMatchListApiPath = "/match/list";
            internal const string GetMatchDetailApiPath = "/match/detail";
            internal const string GetMatchLineupApiPath = "/lineup/list";
        }
    }
}