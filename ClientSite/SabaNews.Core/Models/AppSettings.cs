namespace SabaNews.Core.Models
{
    using System;

    public class AppSettings
    {
        public DateTime MinDateSyncLivescoreData { get; set; }
        public int BatchSizeSyncLivescoreData { get; set; }
    }
}