namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using SabaNews.Livescore.Provider.API.Models.Team;

    public class MatchDetailResponse
    {
        public long MatchId { get; set; }
        public int SportId { get; set; } = 1;
        public int HTotalScore { get; set; } //Home total score
        public int ATotalScore { get; set; } //Away total score
        public int HHalfScore { get; set; } //Home half score
        public int AHalfScore { get; set; } //Home half score
        public int[] FootballAddStartTime { get; set; }
        public int FirstExtraTime => FootballAddStartTime is not null && FootballAddStartTime.Length > 0 ? FootballAddStartTime[0] : 0;
        public int SeccondExtraTime => FootballAddStartTime is not null && FootballAddStartTime.Length > 0 ? FootballAddStartTime[1] : 0;
        public MatchExtraData ExtraData { get; set; }
        public TeamStatistic HomeTeamStatistic => ExtraData?.ToHomeTeamDetails(MatchId);
        public TeamStatistic AwayTeamStatistic => ExtraData?.ToAwayTeamDetails(MatchId);
    }
}