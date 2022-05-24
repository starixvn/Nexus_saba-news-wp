namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services.Criteria.League;

    public interface ILeagueService
    {
        Task<IList<League>> GetMajorLeagueAsync();
        Task<IList<League>> GetLeagueByIdsAsync(GetLeagueByIdsCriteria criteria);
        Task<IList<League>> GetLeaguesAsync();
    }
}