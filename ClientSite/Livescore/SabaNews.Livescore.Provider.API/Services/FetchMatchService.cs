namespace SabaNews.Livescore.Provider.API.Services
{
    using System;
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
                { ApiParams.SportId, paramModel.SportId },
                { ApiParams.MatchId, paramModel.MatchIds is not null && paramModel.MatchIds.Any() ? paramModel.MatchIds : null },
                { ApiParams.LeagueId, paramModel.LeagueId },
                { ApiParams.IdGte, paramModel.MaxMatchId },
                { ApiParams.TimeGte, paramModel.LastUpdatedTime.HasValue ? paramModel.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "startTime", paramModel.StartTime.HasValue ? paramModel.StartTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "endTime", paramModel.EndTime.HasValue ? paramModel.EndTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, paramModel.RowLimit }
            };

            return await httpClient.ExecutePostAsync<List<MatchResponse>>(ApiPath.GetMatchListApiPath, param);
        }

        public async Task<List<MatchDetailResponse>> FetchMatchDetailAsync(SyncMatchParams paramModel)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, paramModel.SportId },
                { ApiParams.TimeGte, paramModel.LastUpdatedTime.HasValue ? paramModel.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, paramModel.RowLimit }
            };
            if (paramModel.MatchIds.Length > 0)
            {
                param.Add(ApiParams.MatchId, paramModel.MatchIds);
            }

            return await httpClient.ExecutePostAsync<List<MatchDetailResponse>>(ApiPath.GetMatchDetailApiPath, param);
        }

        public async Task<List<MatchLineupResponse>> FetchMatchLineupAsync(long[] matchIds)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.MatchId, matchIds.Length > 0 ? matchIds : null },
            };
            return await httpClient.ExecutePostAsync<List<MatchLineupResponse>>(ApiPath.GetMatchLineupApiPath, param);
        }

        public async Task<List<MatchCommentary>> FetchMatchCommentaryAsync(long matchId)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.MatchId, matchId },
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

        public async Task<List<MatchIncident>> FetchMatchIncidentAsync(short sportId, long matchId)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, sportId },
                { ApiParams.MatchId, matchId },
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

        public async Task<List<long>> FetchMatchIncidentUpdateTimeAsync(short sportId, DateTime? lastUpdatedTime)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, sportId },
                { ApiParams.TimeGte, lastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) },
            };
            var incidents = await httpClient.ExecutePostAsync<MatchIncidentUpdateTimeResponse>(ApiPath.GetMatchIncidentApiPath, param);
            if (incidents is not null && incidents.UpdatedMatches is not null)
            {
                return incidents.UpdatedMatches;
            }

            return new List<long>();
        }

        public async Task<IDictionary<string, MatchIncidentResponse>> FetchMultipleMatchIncidentsAsync(short sportId, long[] MatchIds)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, sportId },
                { ApiParams.MatchId, MatchIds },
            };
            var incidents = await httpClient.ExecutePostAsync<Dictionary<string, MatchIncidentResponse>>(ApiPath.GetMultipleIncidentApiPath, param);

            return incidents;
        }

        public async Task<List<MatchDetailResponse>> FetchLiveMatchListAsync(SyncMatchParams paramModel)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, paramModel.SportId }
            };
            if (paramModel.MatchIds is not null && paramModel.MatchIds.Length > 0)
            {
                param.Add(ApiParams.MatchId, paramModel.MatchIds);
            }

            return await httpClient.ExecutePostAsync<List<MatchDetailResponse>>(ApiPath.GetLiveMatchApiPath, param);
        }

        public async Task<List<DeletedMatch>> FetchDeletedMatchAsync()
        {
            return await httpClient.ExecutePostAsync<List<DeletedMatch>>(ApiPath.GetDeletedMatchApiPath, null);
        }

        private static class ApiPath
        {
            internal const string GetMatchListApiPath = "/match/list";
            internal const string GetMatchDetailApiPath = "/match/detail";
            internal const string GetMatchLineupApiPath = "/lineup/list";
            internal const string GetMatchIncidentApiPath = "/match/textlive";
            internal const string GetMultipleIncidentApiPath = "/football/match/textlives";
            internal const string GetMatchCommentaryApiPath = "/match/textlive_en";
            internal const string GetLiveMatchApiPath = "/match/lives";
            internal const string GetDeletedMatchApiPath = "/match/deleted_list";
        }
    }
}