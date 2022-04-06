namespace SabaNews.Livescore.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;

    public interface IPushSignalService
    {
        public Task PushMatchListAsync(IEnumerable<Match> matches);

        public Task PushMatchStatisticAsync(long matchId, Statistic statistic);

        public Task PushMatchLineupAsync(long matchId, LineupDetail lineup);

        public Task PushMatchIncidentAsync(long matchId, IEnumerable<MatchIncident> incidents);
    }
}