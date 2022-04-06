namespace SabaNews.Livescore.Provider.API.Models
{
    using System;

    public class TimeSyncResponse
    {
        public DateTime? MinTime { get; set; }
        public DateTime? MaxTime { get; set; }
        public bool HasMore { get; set; }
    }
}