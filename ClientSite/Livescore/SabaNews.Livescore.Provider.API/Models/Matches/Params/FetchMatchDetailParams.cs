namespace SabaNews.Livescore.Provider.API.Models.Matches.Params
{
    using System;

    public class FetchMatchDetailParams
    {
        public long?[] MatchIds { get; set; }
        public DateTime? LastUpdatedTime { get; set; }
        public int? RowLimit { get; set; }
    }
}