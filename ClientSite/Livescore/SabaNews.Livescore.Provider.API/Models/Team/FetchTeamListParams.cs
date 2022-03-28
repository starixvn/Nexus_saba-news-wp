namespace SabaNews.Livescore.Provider.API.Models.Team
{
    using System;

    public class FetchTeamListParams
    {
        public short? SportId { get; set; }
        public long?[] TeamIds { get; set; }
        public long? IdGte { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int RowLimit { get; set; }
    }
}