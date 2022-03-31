namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.League;

    public interface ILeagueService
    {
        Task<IEnumerable<League>> GetMajorLeagueAsync();
    }
}