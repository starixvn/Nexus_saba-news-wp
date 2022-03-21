namespace SabaNews.Models.LiveScore
{
    public class SoccerMatch
    {
        public int MatchId { get; set; }
        public string Schedule { get; set; }
        public long? MatchTime { get; set; }
        public int LeagueId { get; set; }
        public string LeagueZh { get; set; }
        public string LeagueEn { get; set; }
        public string Color { get; set; }
        public bool IsTop { get; set; }
        public bool IsMatchEndTop { get; set; }
        public string EndTime { get; set; }
        public int SortNumber { get; set; }
        public int ScheduleTicks { get; set; }
        public string DateShareZh { get; set; }
        public string MeiDonDate { get; set; }
        public bool IsMain { get; set; }
        public bool IsFour { get; set; }
        public int? Status { get; set; }
        public SoccerMatchTeam HomeTeam { get; set; }
        public SoccerMatchTeam AwayTeam { get; set; }
    }
}