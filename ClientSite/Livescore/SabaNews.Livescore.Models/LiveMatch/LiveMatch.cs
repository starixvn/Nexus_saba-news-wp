namespace SabaNews.Livescore.Models.LiveMatch
{
    using System.Collections.Generic;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;

    public class LiveMatch : Match
    {
        public TeamStatistic HomeStatistic { get; set; }
        public TeamStatistic AwayStatistic { get; set; }
        public IEnumerable<MatchIncident> Incidents { get; set; }
        public LineupDetail Lineup { get; set; }
    }
}