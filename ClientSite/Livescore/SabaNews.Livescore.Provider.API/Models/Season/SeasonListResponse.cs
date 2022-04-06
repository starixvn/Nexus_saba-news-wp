namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System;

    public class SeasonListResponse
    {
        public short SportId { get; set; }
        public long SeasonId { get; set; }
        public long? LeagueId { get; set; }
        public string Year { get; set; }
        public DateTime? SeasonStartTime { get; set; }
        public DateTime? SeasonEndTime { get; set; }
        public bool HasPlayerStats { get; set; }
        public bool HasTeamStats { get; set; }
        public bool HasTable { get; set; }
        public bool IsCurrent { get; set; }
        public bool IsDelete { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}