namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System;
    using SabaNews.Livescore.Core.Enum;

    public class MatchDetailResponse
    {
        public long MatchId { get; set; }
        public short SportId { get; set; } = (short)SportType.Soccer;
        public short? HTotalScore { get; set; } //Home total score
        public short? ATotalScore { get; set; } //Away total score
        public short? HHalfScore { get; set; } //Home half score
        public short? AHalfScore { get; set; } //Home half score
        public short? GameType { get; set; }
        public short[] AggScore { get; set; }
        public DateTime GameStartTime { get; set; }
        public DateTime GameEndTime { get; set; }
        public short HomeAggScore => AggScore is not null && AggScore.Length > 0 ? AggScore[0] : (short)0;
        public short AwayAggScore => AggScore is not null && AggScore.Length > 0 ? AggScore[1] : (short)0;

        public MatchExtraData ExtraData { get; set; }
    }
}