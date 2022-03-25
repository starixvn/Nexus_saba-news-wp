namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.Team;

    public class FetchTeamService : IFetchTeamService
    {
        private readonly IHttpClientService httpClient;

        public FetchTeamService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<List<TeamListResponse>> FetchTeamListAsync(FetchTeamListParams teamParams)
        {
            var param = new Dictionary<string, object>
            {
                { "sportId", teamParams.SportId },
                { "teamId", teamParams.TeamIds },
                { "idGte", teamParams.IdGte },
                { "timeGte", teamParams.LastUpdatedTime.HasValue ? teamParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { "rowLimit", teamParams.RowLimit }
            };
            return await httpClient.ExecutePostAsync<List<TeamListResponse>>(ApiPath.GetTeamListApiPath, param);
        }

        private static class ApiPath
        {
            internal const string GetTeamListApiPath = "/team/list";
        }
    }
}