namespace SabaNews.Models.LiveScore
{
    public class SoccerMatchCommentary
    {
        public int MatchId { get; set; }
        public int Order { get; set; }
        public int Type { get; set; }
        public int TeamType { get; set; }
        public string TimePoint { get; set; }
        public string Content { get; set; }
    }
}