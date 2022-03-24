namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    public class MatchLineupResponse
    {
        public long MatchId { get; set; }
        public string HFormation { get; set; } //Home team formation
        public string AFormation { get; set; } //Away team formation
    }

    public class TeamLineupResponse
    {
        public long PlayerId { get; set; }
        public int Position { get; set; }
        public int PlayerName { get; set; }
        public int PlayerNumber { get; set; }
        public int[] Coordinate { get; set; }
        public int CoordinateX => Coordinate is not null && Coordinate.Length > 0 ? Coordinate[0] : 0;
        public int CoordinateY => Coordinate is not null && Coordinate.Length > 0 ? Coordinate[1] : 0;
    }
}