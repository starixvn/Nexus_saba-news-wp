namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Season;

    public interface IFetchSeasonService
    {
        Task<List<SeasonListResponse>> FetchSeasonListAsync(FetchSeasonListParams seasonParams);
    }
}