namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.League;

    public class FetchLeagueService : IFetchLeagueService
    {
        private readonly IHttpClientService httpClient;

        public FetchLeagueService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<List<LeagueListResponse>> FetchLeagueListAsync(FetchLeagueListParams leagueParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, leagueParams.SportId },
                { ApiParams.LeagueId, leagueParams.LeagueIds },
                { ApiParams.IdGte, leagueParams.IdGte },
                { ApiParams.TimeGte, leagueParams.LastUpdatedTime.HasValue ? leagueParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, leagueParams.RowLimit },
                { "includeRule", leagueParams.IncludeRule }
            };
            return await httpClient.ExecutePostAsync<List<LeagueListResponse>>(ApiPath.GetLeagueListApiPath, param);
        }


        private static class ApiPath
        {
            internal const string GetLeagueListApiPath = "/league/list";
        }
    }
}