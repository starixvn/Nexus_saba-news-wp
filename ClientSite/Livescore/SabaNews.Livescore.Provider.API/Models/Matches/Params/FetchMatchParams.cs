namespace SabaNews.Livescore.Provider.API.Models.Matches.Params
{
    using System;

    public class FetchMatchParams
    {
        public long?[] MatchIds { get; set; }
        public long? LeagueId { get; set; }
        public DateTime? StartTime { get; set; }
        public DateTime? EndTime { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int? RowLimit { get; set; }
    }
}