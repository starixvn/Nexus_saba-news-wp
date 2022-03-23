namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services.Criteria.Match;

    public interface IMatchService
    {
        Task<IEnumerable<Match>> GetMatchByEventDateAsync(GetMatchByEventDateCriteria criteria);
    }
}