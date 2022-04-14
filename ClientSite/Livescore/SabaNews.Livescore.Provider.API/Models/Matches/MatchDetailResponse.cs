namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System;
    using SabaNews.Livescore.Core.Enum;

    public class MatchDetailResponse
    {
        public long MatchId { get; set; }
        public int SportId { get; set; } = (int)SportType.Soccer;
        public int? HTotalScore { get; set; } //Home total score
        public int? ATotalScore { get; set; } //Away total score
        public int? HHalfScore { get; set; } //Home half score
        public int? AHalfScore { get; set; } //Home half score
        public int? GameType { get; set; }
        public int[] AggScore { get; set; }
        public DateTime GameStartTime { get; set; }
        public DateTime GameEndTime { get; set; }
        public DateTime UpdatedAt { get; set; }
        public int HomeAggScore => AggScore is not null && AggScore.Length > 0 ? AggScore[0] : 0;
        public int AwayAggScore => AggScore is not null && AggScore.Length > 0 ? AggScore[1] : 0;

        public MatchExtraData ExtraData { get; set; }
    }
}