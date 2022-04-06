namespace SabaNews.Livescore.Models.Match
{
    using System.Collections.Generic;

    public class MatchDetailPostMatch
    {
        public IEnumerable<MatchIncidentEvent> Incidents { get; set; }

        public TeamStatistic HomeStatistic { get; set; }

        public TeamStatistic AwayStatistic { get; set; }
    }
}
