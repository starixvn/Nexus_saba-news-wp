namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Helpers;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services.Interfaces;

    public class FetchMatchService : IFetchMatchService
    {
        private readonly IHttpClientService httpClient;

        public FetchMatchService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<List<MatchResponse>> FetchMatchListAsync(SyncMatchParams paramModel)
        {
            var param = ProviderApiHelpers.InitParams();
            param.Add("matchId", paramModel.MatchIds);
            param.Add("leagueId", paramModel.LeagueId);
            param.Add("timeGte", paramModel.LastUpdatedTime.HasValue ? paramModel.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null);
            param.Add("startTime", paramModel.StartTime.HasValue ? paramModel.StartTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null);
            param.Add("endTime", paramModel.EndTime.HasValue ? paramModel.EndTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null);

            if (paramModel.RowLimit is not null)
            {
                param.Add("rowLimit", paramModel.RowLimit);
            }

            var matches = await httpClient.ExecutePostAsync<List<MatchResponse>>(ApiPath.GetMatchListApiPath, param);

            return matches;
        }

        private static class ApiPath
        {
            public const string GetMatchListApiPath = "/match/list";
        }
    }
}