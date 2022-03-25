namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    public class MatchDetailResponse
    {
        public long MatchId { get; set; }
        public int SportId { get; set; } = 1;
        public int? HTotalScore { get; set; } //Home total score
        public int? ATotalScore { get; set; } //Away total score
        public int? HHalfScore { get; set; } //Home half score
        public int? AHalfScore { get; set; } //Home half score
        public int[] AggScore { get; set; }
        public int HomeAggScore => AggScore is not null && AggScore.Length > 0 ? AggScore[0] : 0;
        public int AwayAggScore => AggScore is not null && AggScore.Length > 0 ? AggScore[1] : 0;
        public MatchExtraData ExtraData { get; set; }
    }
}