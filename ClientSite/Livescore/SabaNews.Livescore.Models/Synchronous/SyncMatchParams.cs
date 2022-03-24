namespace SabaNews.Livescore.Models.Synchronous
{
    using System;

    public class SyncMatchParams
    {
        public int SportId { get; set; } = 1;
        public long[] MatchIds { get; set; }
        public long? LeagueId { get; set; }
        public DateTime? StartTime { get; set; }
        public DateTime? EndTime { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int? RowLimit { get; set; }
    }
}