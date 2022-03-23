namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Helpers;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Models.Matches.Params;
    using SabaNews.Livescore.Provider.API.Services.Interfaces;

    public class FetchMatchService : IFetchMatchService
    {
        private readonly IHttpClientService httpClient;

        public FetchMatchService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<List<MatchResponse>> FetchMatchListAsync(FetchMatchParams parameters)
        {
            var param = ProviderApiHelpers.InitParams();
            param.Add("matchId", parameters.MatchIds);
            param.Add("leagueId", parameters.LeagueId);
            param.Add("timeGte", parameters.LastUpdatedTime.HasValue ? parameters.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null);
            param.Add("startTime", parameters.StartTime.HasValue ? parameters.StartTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null);
            param.Add("endTime", parameters.EndTime.HasValue ? parameters.EndTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null);

            if (parameters.RowLimit is not null)
            {
                param.Add("rowLimit", parameters.RowLimit);
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