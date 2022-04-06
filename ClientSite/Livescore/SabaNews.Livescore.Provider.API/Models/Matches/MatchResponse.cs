namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System;

    public class MatchResponse
    {
        public long MatchId { get; set; }
        public int SportId { get; set; } = 1;
        public long? CombinedId { get; set; }
        public string Schedule { get; set; }
        public long? LeagueId { get; set; }
        public long? SeasonId { get; set; }
        public long? StageId { get; set; }
        public int? GroupNum { get; set; }
        public int? Round { get; set; }
        public string RoundType { get; set; }
        public int? Neutral { get; set; }
        public DateTime GameStartTime { get; set; }
        public DateTime GameEndTime { get; set; }
        public DateTime UpdatedAt { get; set; }
        public int HId { get; set; } //HomeTeamId
        public int AId { get; set; } //AwayTeamId
        public long? RelatedId { get; set; }
        public bool IsLineUp { get; set; }
        public bool IsIntelligence { get; set; }
        public int? GameType { get; set; }
        public DateTime? FootballAddStartTime { get; set; }
        public DateTime? FootballStartTime { get; set; }
        public DateTime? FootballNextHalfStartTime { get; set; }
    }
}