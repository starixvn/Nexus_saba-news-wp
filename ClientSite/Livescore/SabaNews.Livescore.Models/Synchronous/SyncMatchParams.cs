namespace SabaNews.Livescore.Models.Synchronous
{
    using System;
    using SabaNews.Livescore.Core.Enum;

    public class SyncMatchParams
    {
        public short SportId { get; set; } = (short)SportType.Soccer;
        public long[] MatchIds { get; set; }
        public long? LeagueId { get; set; }
        public long? MaxMatchId { get; set; }
        public DateTime? StartTime { get; set; }
        public DateTime? EndTime { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int? RowLimit { get; set; }
    }
}