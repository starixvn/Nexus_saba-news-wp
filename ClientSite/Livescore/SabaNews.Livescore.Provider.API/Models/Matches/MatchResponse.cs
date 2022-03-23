namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System;
    using Newtonsoft.Json;

    public class MatchResponse
    {
        public long MatchId { get; set; }
        public long? CombinedId { get; set; }
        public string Schedule { get; set; }
        public long LeagueId { get; set; }
        public long SeasonId { get; set; }
        public long StageId { get; set; }
        public int GroupNum { get; set; }
        public int Round { get; set; }
        public string RoundType { get; set; }
        public int Neutral { get; set; }
        public DateTime GameStartTime { get; set; }
        public DateTime GameEndTime { get; set; }
        public DateTime UpdatedAt { get; set; }

        [JsonProperty("hId")]
        public int HomeTeamId { get; set; }

        [JsonProperty("aId")]
        public int AwayTeamId { get; set; }

        public long? RelatedId { get; set; }
        public bool IsLineUp { get; set; }
        public bool IsIntelligence { get; set; }
        public int GameType { get; set; }
    }
}