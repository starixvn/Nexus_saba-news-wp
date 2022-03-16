namespace SabaNews.Livescore.Provider.API.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Core.Services.Interfaces;
    using SabaNews.Livescore.Provider.API.Constants;
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
            var param = new Dictionary<string, object> {
                { "matchId", matchId }
            };

            var match = await httpClient.ExecutePostAsync<MatchResponseWrapper>(ApiPath.GetMatchByIdPath, param);
            return match?.Match;
        }

        public async Task<List<MatchResponse>> FetchMatchListByDateAsync(DateTime date)
        {
            var param = new Dictionary<string, object> {
                { "date", date.ToString("yyyy-MM-dd")}
            };
            var matches = await httpClient.ExecutePostAsync<MatchListResponseWrapper>(ApiPath.GetMatchByDatePath, param);

            return matches?.Match;
        }
    }
}