namespace SabaNews.Livescore.Provider.API.Models.League
{
    using System;

    public class FetchLeagueListParams
    {
        public short? SportId { get; set; }
        public long?[] LeagueIds { get; set; }
        public long? IdGte { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int RowLimit { get; set; }
        public string IncludeRule { get; set; }
    }
}