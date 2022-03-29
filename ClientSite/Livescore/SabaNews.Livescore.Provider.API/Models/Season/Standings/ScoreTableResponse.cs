namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System;
    using System.Collections.Generic;

    public class ScoreTableResponse
    {
        public string GroupName { get; set; }
        public string Conference { get; set; }
        public DateTime UpdatedAt { get; set; }
        public IList<LeaderboardResponse> Rank { get; set; }
    }
}