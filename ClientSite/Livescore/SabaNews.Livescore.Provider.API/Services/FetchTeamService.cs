namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.Team;

    public class FetchTeamService : IFetchTeamService
    {
        private readonly IHttpClientService httpClient;
        private readonly ILogServiceProvider logServiceProvider;

        public FetchTeamService(IHttpClientService httpClient, ILogServiceProvider logServiceProvider)
        {
            this.httpClient = httpClient;
            this.logServiceProvider = logServiceProvider;
        }

        public async Task<HeadToHeadResponse> FetchHeadToHeadAsync(long homeTeamId, long awayTeamId)
        {
            var param = new Dictionary<string, object>
                {
                    { "teamId", new long[] { homeTeamId, awayTeamId} }
                };

            return await httpClient.ExecutePostAsync<HeadToHeadResponse>(ApiPath.GetTeamHeadToHeadApiPath, param);
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
            internal const string GetTeamHeadToHeadApiPath = "/team/history";
        }
    }
}