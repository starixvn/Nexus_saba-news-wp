namespace SabaNews.Livescore.Models.League
{
    using System;

    public class LeagueSession
    {
        public long SeasonId { get; set; }
        public DateTimeOffset StartDate { get; }

        public DateTimeOffset EndDate { get; }

        public string Year { get; }

        public string LeagueId { get; }
    }
}