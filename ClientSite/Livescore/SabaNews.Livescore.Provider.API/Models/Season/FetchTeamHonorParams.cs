namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System;

    public class FetchTeamHonorParams
    {
        public long? IdGte { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int RowLimit { get; set; }
    }
}