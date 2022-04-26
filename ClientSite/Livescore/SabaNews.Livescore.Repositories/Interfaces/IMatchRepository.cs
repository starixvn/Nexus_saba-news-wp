namespace SabaNews.Livescore.Repositories
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Fanex.Data.Repository;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;

    public interface IMatchRepository
    {
        Task<IList<Match>> GetMatchByDateAsync(ICriteria criteria);

        Task<MatchDetail> GetMatchDetaiAsync(ICriteria criteria);

        Task<Match> GetMatchByIdAsync(ICriteria criteria);

        Task<HeadToHeadDetail> GetHeadToHeadAsync(ICriteria criteria);

        Task<IList<Match>> GetMatchByIdsAsync(ICriteria criteria);
    }
}