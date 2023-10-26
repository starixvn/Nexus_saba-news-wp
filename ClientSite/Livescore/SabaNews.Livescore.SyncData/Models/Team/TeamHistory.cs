namespace SabaNews.Livescore.SyncData.Models.Team
{
    public class TeamHistory
    {
        public long MatchId { get; set; }
        public long ReferenceMatchId { get; set; }
        public long HomeTeamId { get; set; }
        public long AwayTeamId { get; set; }
        public int? HomeTotalScore { get; set; }
        public int? AwayTotalScore { get; set; }
        public int? HomeHalfScore { get; set; }
        public int? AwayHalfScore { get; set; }
    }
}