namespace SabaNews.Livescore.Models.Match
{
    using System.Collections.Generic;
    using SabaNews.Livescore.Models.Lineup;

    public class LiveMatchDetail
    {
        public Match MatchInfo { get; set; }
        public TeamStatistic HomeStatistic { get; set; }
        public TeamStatistic AwayStatistic { get; set; }
        public IEnumerable<MatchIncident> Incidents { get; set; }
        public Lineup Lineup { get; set; }
        public IEnumerable<LineupTeam> HomeLineup { get; set; }
        public IEnumerable<LineupTeam> AwayLineup { get; set; }
    }
}