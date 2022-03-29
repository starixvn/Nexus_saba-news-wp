namespace SabaNews.Livescore.SyncData.Models.Season
{
    using System;
    using SabaNews.Livescore.Provider.API.Models.Season;

    public class SeasonStandings : LeaderboardResponse
    {
        public long? StageId { get; set; }
        public string StageName { get; set; }
        public string GroupName { get; set; }
        public string Conference { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}