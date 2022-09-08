namespace SabaNews.Livescore.Models.League
{
    using System;

    public class Season
    {
        public long LeagueId { get; set; }
        public long SeasonId { get; set; }
        public DateTime? StartTime { get; }
        public DateTime? EndTime { get; }
        public string Year { get; }
    }
}