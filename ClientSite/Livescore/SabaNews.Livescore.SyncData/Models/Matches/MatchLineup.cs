namespace SabaNews.Livescore.SyncData.Models.Matches
{
    public class MatchLineup
    {
        public long MatchId { get; set; }
        public string HomeFormation { get; set; }
        public string AwayFormation { get; set; }
    }

    public class PlayerLineup
    {
        public long MatchId { get; set; }
        public long PlayerId { get; set; }
        public int Position { get; set; }
        public string PlayerName { get; set; }
        public int PlayerNumber { get; set; }
        public bool IsHomeTeam { get; set; }
        public bool IsSubstitute { get; set; }
        public int CoordinateX { get; set; }
        public int CoordinateY { get; set; }
        public string Role { get; set; }
    }
}