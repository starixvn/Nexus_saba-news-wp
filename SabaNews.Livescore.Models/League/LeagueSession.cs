namespace SabaNews.Livescore.Models.League
{
    using System;
    using SabaNews.Livescore.Core;

    public class LeagueSession : BaseModel
    {
        public DateTimeOffset StartDate { get; }

        public DateTimeOffset EndDate { get; }

        public string Year { get; }

        public string LeagueId { get; }
    }
}