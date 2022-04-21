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
                { "matchId", paramModel.MatchIds is not null && paramModel.MatchIds.Any() ? paramModel.MatchIds : null },
                { "leagueId", paramModel.LeagueId },
                { "idGte", paramModel.MaxMatchId },
                { "timeGte", paramModel.LastUpdatedTime.HasValue ? paramModel.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "startTime", paramModel.StartTime.HasValue ? paramModel.StartTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "endTime", paramModel.EndTime.HasValue ? paramModel.EndTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "rowLimit", paramModel.RowLimit }
            };

            var matches = await httpClient.ExecutePostAsync<List<MatchResponse>>(ApiPath.GetMatchListApiPath, param);

            return matches;
        }

        public async Task<List<MatchDetailResponse>> FetchMatchDetailAsync(SyncMatchParams paramModel)
        {
            var param = new Dictionary<string, object>
            {
                { "sportId", paramModel.SportId },
                { "timeGte", paramModel.LastUpdatedTime.HasValue ? paramModel.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "rowLimit", paramModel.RowLimit }
            };

            if (paramModel.MatchIds.Length > 0)
            {
                param.Add("matchId", paramModel.MatchIds);
            }

            var matcheDetails = await httpClient.ExecutePostAsync<List<MatchDetailResponse>>(ApiPath.GetMatchDetailApiPath, param);

            return matcheDetails;
        }

        public async Task<List<MatchLineupResponse>> FetchMatchLineupAsync(long[] matchIds)
        {
            var param = new Dictionary<string, object>
            {
                { "matchId", matchIds.Length > 0 ? matchIds : null },
            };

            var matcheDetails = await httpClient.ExecutePostAsync<List<MatchLineupResponse>>(ApiPath.GetMatchLineupApiPath, param);

            return matcheDetails;
        }

        public async Task<List<MatchCommentary>> FetchMatchCommentaryAsync(long matchId)
        {
            var param = new Dictionary<string, object>
            {
                { "matchId", matchId },
            };

            var commentary = await httpClient.ExecutePostAsync<MatchCommentaryResponse>(ApiPath.GetMatchCommentaryApiPath, param);

            if (commentary is not null)
            {
                foreach (var item in commentary.Commentaries)
                {
                    item.MatchId = matchId;
                }

                return commentary.Commentaries;
            }

            return new List<MatchCommentary>();
        }

        public async Task<List<MatchIncident>> FetchMatchIncidentAsync(int sportId, long matchId)
        {
            var param = new Dictionary<string, object>
            {
                { "sportId", sportId },
                { "matchId", matchId },
            };

            var incidents = await httpClient.ExecutePostAsync<MatchIncidentResponse>(ApiPath.GetMatchIncidentApiPath, param);

            if (incidents is not null && incidents.Incidents is not null)
            {
                foreach (var item in incidents.Incidents)
                {
                    item.MatchId = matchId;
                }

                return incidents.Incidents;
            }

            return new List<MatchIncident>();
        }

        public async Task<List<MatchDetailResponse>> FetchLiveMatchListAsync(SyncMatchParams paramModel)
        {
            var param = new Dictionary<string, object>
            {
                { "sportId", paramModel.SportId }
            };

            if (paramModel.MatchIds is not null && paramModel.MatchIds.Length > 0)
            {
                param.Add("matchId", paramModel.MatchIds);
            }

            var matcheDetails = await httpClient.ExecutePostAsync<List<MatchDetailResponse>>(ApiPath.GetLiveMatchApiPath, param);

            return matcheDetails;
        }

        public async Task<List<DeletedMatch>> FetchDeletedMatchAsync()
        {
            var delMatches = await httpClient.ExecutePostAsync<List<DeletedMatch>>(ApiPath.GetDeletedMatchApiPath, null);

            return delMatches;
        }

        private static class ApiPath
        {
            internal const string GetMatchListApiPath = "/match/list";
            internal const string GetMatchDetailApiPath = "/match/detail";
            internal const string GetMatchLineupApiPath = "/lineup/list";
            internal const string GetMatchIncidentApiPath = "/match/textlive";
            internal const string GetMatchCommentaryApiPath = "/match/textlive_en";
            internal const string GetLiveMatchApiPath = "/match/lives";
            internal const string GetDeletedMatchApiPath = "/match/deleted_list";
        }
    }
}