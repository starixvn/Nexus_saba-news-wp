namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Team;

    public interface IFetchTeamService
    {
        Task<List<TeamListResponse>> FetchTeamListAsync(FetchTeamListParams teamParams);

        Task<HeadToHeadResponse> FetchHeadToHeadAsync(long matchId, long homeTeamId, long awayTeamId);
    }
}