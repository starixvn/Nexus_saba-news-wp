namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.Player;

    public class FetchPlayerService : IFetchPlayerService
    {
        private readonly IHttpClientService httpClient;

        public FetchPlayerService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<IDictionary<string, List<PlayerListResponse>>> FetchPlayerListAsync(FetchPlayerListParams playerParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, playerParams.SportId },
                { "playerId", playerParams.PlayerIds },
                { ApiParams.TeamId, playerParams.TeamId },
                { ApiParams.IdGte, playerParams.IdGte },
                { ApiParams.TimeGte, playerParams.LastUpdatedTime.HasValue ? playerParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, playerParams.RowLimit }
            };
            return await httpClient.ExecutePostAsync<Dictionary<string, List<PlayerListResponse>>>(ApiPath.GetPlayerListApiPath, param);
        }

        private static class ApiPath
        {
            internal const string GetPlayerListApiPath = "/player/list";
        }
    }
}