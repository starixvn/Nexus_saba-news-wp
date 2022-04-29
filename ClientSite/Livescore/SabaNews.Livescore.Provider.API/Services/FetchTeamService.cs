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

        public async Task<HeadToHeadResponse> FetchHeadToHeadAsync(long matchId, long homeTeamId, long awayTeamId)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.TeamId, new[] {homeTeamId, awayTeamId} }
            };

            var h2h = await httpClient.ExecutePostAsync<HeadToHeadResponse>(ApiPath.GetTeamHeadToHeadApiPath, param);

            if (h2h is not null)
            {
                h2h.SourceMatchId = matchId;
            }
            return h2h;
        }

        public async Task<List<TeamListResponse>> FetchTeamListAsync(FetchTeamListParams teamParams)
        {
            var param = new Dictionary<string, object>
            {
                { ApiParams.SportId, teamParams.SportId },
                { ApiParams.TeamId, teamParams.TeamIds },
                { ApiParams.IdGte, teamParams.IdGte },
                { ApiParams.TimeGte, teamParams.LastUpdatedTime.HasValue ? teamParams.LastUpdatedTime.Value.ToString(CommonConstants.OutgoingDatetimeFormat) : null },
                { ApiParams.RowLimit, teamParams.RowLimit }
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