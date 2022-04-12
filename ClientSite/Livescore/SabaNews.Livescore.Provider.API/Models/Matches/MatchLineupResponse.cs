namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System.Collections.Generic;

    public class MatchLineupResponse
    {
        public long MatchId { get; set; }
        public string HFormation { get; set; } //Home team formation
        public string AFormation { get; set; } //Away team formation
        public List<TeamLineupResponse> HLineup { get; set; }
        public List<TeamLineupResponse> HBench { get; set; }
        public List<TeamLineupResponse> ALineup { get; set; }
        public List<TeamLineupResponse> ABench { get; set; }
    }

    public class TeamLineupResponse
    {
        public long PlayerId { get; set; }
        public int Position { get; set; }
        public string PlayerName { get; set; }
        public int PlayerNumber { get; set; }
        public LineupCoordinate Coordinate { get; set; }
        public string Role { get; set; }
    }

    public class LineupCoordinate
    {
        public int X { get; set; }
        public int Y { get; set; }
    }
}