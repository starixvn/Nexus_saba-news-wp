namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Repositories;
    using SabaNews.Livescore.Services.Criteria.Match;

    public class MatchService : IMatchService
    {
        private readonly IMatchRepository matchRepository;

        public MatchService(IMatchRepository matchRepository)
        {
            this.matchRepository = matchRepository;
        }

        public async Task<IEnumerable<Match>> GetMatchByEventDateAsync(GetMatchByEventDateCriteria criteria)
        {
            return await matchRepository.GetMatchByEventDateAsync(criteria);
        }
    }
}