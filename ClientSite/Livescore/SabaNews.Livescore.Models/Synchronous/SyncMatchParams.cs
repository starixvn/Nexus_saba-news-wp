namespace SabaNews.Livescore.Models.Synchronous
{
    using System;
    using SabaNews.Livescore.Core.Enum;

    public class SyncMatchParams
    {
        public int SportId { get; set; } = (int)SportType.Soccer;
        public long[] MatchIds { get; set; }
        public long? LeagueId { get; set; }
        public DateTime? StartTime { get; set; }
        public DateTime? EndTime { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int? RowLimit { get; set; }
    }
}