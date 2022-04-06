namespace SabaNews.Livescore.SyncData.Models.Matches
{
    internal class MatchScoreInput
    {
        public long MatchId { get; set; }
        public int HomeTotalScore { get; set; }
        public int AwayTotalScore { get; set; }
        public int HomeHalfScore { get; set; }
        public int AwayHalfScore { get; set; }
        public int HomeAggregateScore { get; set; }
        public int AwayAggregateScore { get; set; }
    }
}