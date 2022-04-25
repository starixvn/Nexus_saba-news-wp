namespace SabaNews.Core.Models
{
    using System;

    public class AppSettings
    {
        public DateTime MinDateSyncLivescoreData { get; set; }
        public int BatchSizeSyncLivescoreData { get; set; }
        public int BatchSizeSyncMatches { get; set; }
        public bool IsSkipSyncFinishedMatches { get; set; }
        public int HeadToHeadMaxFutureDays { get; set; }
        public int SyncHeadToHeadRowLimit { get; set; }
    }
}