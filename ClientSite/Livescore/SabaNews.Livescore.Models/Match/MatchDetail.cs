namespace SabaNews.Livescore.Models.Match
{
    using SabaNews.Livescore.Models.Lineup;
    using System.Collections.Generic;

    public class MatchDetail
    {
        public MathGeneralInfo GeneralInfo { get; set; }
        public Statistic Statistic { get; set; }
        public IEnumerable<MatchIncident> Incidents { get; set; }
        public LineupDetail Lineup { get; set; }
    }
}
