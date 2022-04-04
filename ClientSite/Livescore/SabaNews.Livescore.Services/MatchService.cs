namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Extensions;
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
            IEnumerable<Match> matches = await matchRepository.GetMatchByEventDateAsync(criteria);
            foreach(var match in matches)
            {
                match.StartTime = match.StartTime.ConvertFromUTC();
                match.EndTime = match.EndTime.ConvertFromUTC();
            }

            return matches;
        }
    }
}