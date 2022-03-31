namespace SabaNews.Livescore.Provider.API.Models.Player
{
    using System;

    public class FetchPlayerListParams
    {
        public short SportId { get; set; }
        public long?[] PlayerIds { get; set; }
        public long? TeamId { get; set; }
        public long? IdGte { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int RowLimit { get; set; }
    }
}