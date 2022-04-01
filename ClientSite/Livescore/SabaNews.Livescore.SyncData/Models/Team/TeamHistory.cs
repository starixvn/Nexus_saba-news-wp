namespace SabaNews.Livescore.SyncData.Models.Team
{
    internal class TeamHistory
    {
        public long MatchId { get; set; }
        public long HomeTeamId { get; set; }
        public long AwayTeamId { get; set; }
        public int TotalHomeScore { get; set; }
        public int TotalAwayScore { get; set; }
        public int HalfHomeScore { get; set; }
        public int HalfAwayScore { get; set; }
    }
}