namespace SabaNews.Livescore.Models.Match
{
    using SabaNews.Livescore.Models.Lineup;
    using System.Collections.Generic;

    public class MatchDetail
    {
        public MathGeneralInfo GeneralInfo { get; set; }
        public TeamStatistic HomeTeam { get; set; }
        public TeamStatistic AwayTeam { get; set; }
        public IEnumerable<MatchIncidentEvent> Incidents { get; set; }
        public LineupDetail Lineup { get; set; }
    }
}
