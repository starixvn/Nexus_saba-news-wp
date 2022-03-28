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
                { "idGte", seasonParams.IdGte ?? 1 },
                { "timeGte", seasonParams.LastUpdatedTime.HasValue ? seasonParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "rowLimit", seasonParams.RowLimit },
                { "isDelete", seasonParams.IsDeleted ? 1 : 0 }
            };
            return await httpClient.ExecutePostAsync<List<SeasonListResponse>>(ApiPath.GetSeasonListApiPath, param);
        }

        public async Task<List<StageListResponse>> FetchStageListAsync(FetchSeasonListParams seasonParams)
        {
            var param = new Dictionary<string, object>
            {
                { "idGte", seasonParams.IdGte ?? 1 },
                { "timeGte", seasonParams.LastUpdatedTime.HasValue ? seasonParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "rowLimit", seasonParams.RowLimit },
                { "isDelete", seasonParams.IsDeleted ? 1 : 0 }
            };
            return await httpClient.ExecutePostAsync<List<StageListResponse>>(ApiPath.GetStageListApiPath, param);
        }

        private static class ApiPath
        {
            internal const string GetSeasonListApiPath = "/season/list";
            internal const string GetStageListApiPath = "/stage/list";
        }
    }
}