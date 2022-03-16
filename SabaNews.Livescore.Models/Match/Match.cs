namespace SabaNews.Livescore.Models.Match
{
    using System;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Team;

    public class Match
    {
        public int MatchId { get; set; }

        public string Stadium { get; set; }

        public DateTime EventDate { get; set; }

        public string Region { get; set; }

        public League League { get; set; }

        public string Status { get; set; }

        public Team Home { get; set; }

        public Team Away { get; set; }
    }
}