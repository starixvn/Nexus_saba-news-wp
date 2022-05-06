namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.Season;

    public class FetchSeasonService : IFetchSeasonService
    {
        private readonly IHttpClientService httpClient;

        public FetchSeasonService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<List<SeasonListResponse>> FetchSeasonListAsync(FetchSeasonListParams seasonParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.IdGte, seasonParams.IdGte ?? 1 },
                { ApiParams.TimeGte, seasonParams.LastUpdatedTime.HasValue ? seasonParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, seasonParams.RowLimit },
                { "isDelete", seasonParams.IsDeleted ? 1 : 0 }
            };
            return await httpClient.ExecutePostAsync<List<SeasonListResponse>>(ApiPath.GetSeasonListApiPath, param);
        }

        public async Task<List<StageListResponse>> FetchStageListAsync(FetchSeasonListParams seasonParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.IdGte, seasonParams.IdGte ?? 1 },
                { ApiParams.TimeGte, seasonParams.LastUpdatedTime.HasValue ? seasonParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, seasonParams.RowLimit },
                { "isDelete", seasonParams.IsDeleted ? 1 : 0 }
            };
            return await httpClient.ExecutePostAsync<List<StageListResponse>>(ApiPath.GetStageListApiPath, param);
        }

        public async Task<List<T>> FetchSeasonStandingsAsync<T>(FetchSeasonStandingsParams seasonStadingsParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, seasonStadingsParams.SportId },
                { "seasonId", seasonStadingsParams.SeasonId },
                { ApiParams.TimeGte, seasonStadingsParams.LastUpdatedTime.HasValue ? seasonStadingsParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null }
            };
            return await httpClient.ExecutePostAsync<List<T>>(ApiPath.GetSeasonStandingsApiPath, param);
        }

        public async Task<List<TeamHonorResponse>> FetchTeamHonorAsync(FetchTeamHonorParams teamHonorParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.IdGte, teamHonorParams.IdGte },
                { ApiParams.TimeGte, teamHonorParams.LastUpdatedTime.HasValue ? teamHonorParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, teamHonorParams.RowLimit }
            };
            return await httpClient.ExecutePostAsync<List<TeamHonorResponse>>(ApiPath.GetSeasonTeamHonorApiPath, param);
        }

        public async Task<List<PlayerHonorResponse>> FetchPlayerHonorAsync(FetchTeamHonorParams teamHonorParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.IdGte, teamHonorParams.IdGte },
                { ApiParams.TimeGte, teamHonorParams.LastUpdatedTime.HasValue ? teamHonorParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, teamHonorParams.RowLimit }
            };
            return await httpClient.ExecutePostAsync<List<PlayerHonorResponse>>(ApiPath.GetSeasonPlayerHonorApiPath, param);
        }

        private static class ApiPath
        {
            internal const string GetSeasonListApiPath = "/season/list";
            internal const string GetStageListApiPath = "/stage/list";
            internal const string GetSeasonStandingsApiPath = "/season/board/team";
            internal const string GetSeasonTeamHonorApiPath = "/season/honor/team/football";
            internal const string GetSeasonPlayerHonorApiPath = "/season/honor/player/football";
        }
    }
}