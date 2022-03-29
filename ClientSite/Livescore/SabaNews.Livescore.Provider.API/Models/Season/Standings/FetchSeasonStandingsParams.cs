namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System;

    public class FetchSeasonStandingsParams
    {
        public short SportId { get; set; }
        public long? SeasonId { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
    }
}