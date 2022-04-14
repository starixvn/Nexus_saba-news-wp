namespace SabaNews.Livescore.Provider.API.Models.Team
{
    using System.Collections.Generic;
    using Newtonsoft.Json;

    public class HeadToHeadResponse
    {
        public long SourceMatchId { get; set; }

        [JsonProperty("hTeamId")]
        public long HomeTeamId { get; set; }

        [JsonProperty("hRecord")]
        public List<TeamHistoryRecord> HomeTeamRecord { get; set; }

        [JsonProperty("aTeamId")]
        public long AwayTeamId { get; set; }

        [JsonProperty("aRecord")]
        public List<TeamHistoryRecord> AwayTeamRecord { get; set; }

        public List<TeamAgainstRecord> AgainstRecord { get; set; }
    }

    public class TeamHistoryRecord
    {
        public bool IsHost { get; set; }
        public long MatchId { get; set; }
        public long EnemyId { get; set; }
        public int?[] TotalScores { get; set; }
        public int?[] HalfScores { get; set; }
    }

    public class TeamAgainstRecord
    {
        public long MatchId { get; set; }

        [JsonProperty("hIdVsAid")]
        public long[] HomeIdVsAwayId { get; set; }

        public int?[] TotalScores { get; set; }
        public int?[] HalfScores { get; set; }
    }
}