namespace SabaNews.Livescore.Provider.API.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
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

        public async Task<MatchResponse> FetchMatchByIdAync(int matchId)
        {
            var param = ProviderApiHelpers.InitParams();
            param.Add("matchId", matchId);

            var match = await httpClient.ExecutePostAsync<List<MatchResponse>>(ApiPath.GetMatchListApiPath, param);
            return match.FirstOrDefault();
        }

        public async Task<List<MatchResponse>> FetchMatchesByDateAsync(DateTime date)
        {
            var param = ProviderApiHelpers.InitParams();
            var startTime = date.Date;
            var endTime = date.Date.AddDays(1).AddSeconds(-1);
            param.Add("startTime", startTime.ToString(CommonConstants.OutgoingDatetimeFormat));
            param.Add("endTime", endTime.ToString(CommonConstants.OutgoingDatetimeFormat));
            var matches = await httpClient.ExecutePostAsync<List<MatchResponse>>(ApiPath.GetMatchListApiPath, param);

            return matches;
        }

        public async Task<List<MatchResponse>> FetchMatchesByDateRangeAsync(DateTime from, DateTime to)
        {
            var param = ProviderApiHelpers.InitParams();
            to = to.Date.AddDays(1).AddSeconds(-1);
            param.Add("startTime", from.Date.ToString(CommonConstants.OutgoingDatetimeFormat));
            param.Add("endTime", to.ToString(CommonConstants.OutgoingDatetimeFormat));

            var matches = await httpClient.ExecutePostAsync<List<MatchResponse>>(ApiPath.GetMatchListApiPath, param);

            return matches;
        }

        private static class ApiPath
        {
            public const string GetMatchListApiPath = "/match/list";
        }
    }
}