namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System;

    public class FetchSeasonListParams
    {
        public string Token { get; set; }
        public long? IdGte { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int RowLimit { get; set; }
        public bool IsDeleted { get; set; }
    }
}