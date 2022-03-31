namespace SabaNews.Models.LiveScore
{
    public class SoccerMatchLineupTeam
    {
        public int MatchId { get; set; }
        public int TeamId { get; set; }
        public int PlayerId { get; set; }
        public string PlayerName { get; set; }
        public int IsSubstitute { get; set; }
        public int PlayerNumber { get; set; }
        public int Position { get; set; }
        public int CoordinateX { get; set; }
        public int CoordinateY { get; set; }
    }
}
