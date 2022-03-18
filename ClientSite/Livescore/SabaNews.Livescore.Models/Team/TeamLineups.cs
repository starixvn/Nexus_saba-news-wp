namespace SabaNews.Livescore.Models.Team
{
    public class TeamLineups
    {
        public int PlayerId { get; set; }

        public string PlayerName { get; set; }

        public bool IsFirst { get; set; }

        public int ShirtNumber { get; set; }

        public int Position { get; set; }

        public int PositionX { get; set; }

        public int PositionY { get; set; }

        public string CoachName { get; set; }

        public string CoachNationality { get; set; }
    }
}