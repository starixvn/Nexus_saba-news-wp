namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Season;

    public interface IFetchSeasonService
    {
        Task<List<SeasonListResponse>> FetchSeasonListAsync(FetchSeasonListParams seasonParams);

        Task<List<StageListResponse>> FetchStageListAsync(FetchSeasonListParams seasonParams);

        Task<List<T>> FetchSeasonStandingsAsync<T>(FetchSeasonStandingsParams seasonStadingsParams);

        Task<List<TeamHonorResponse>> FetchTeamHonorAsync(FetchTeamHonorParams teamHonorParams);

        Task<List<PlayerHonorResponse>> FetchPlayerHonorAsync(FetchTeamHonorParams teamHonorParams);
    }
}