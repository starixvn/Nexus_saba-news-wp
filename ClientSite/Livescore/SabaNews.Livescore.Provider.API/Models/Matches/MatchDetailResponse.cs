namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using SabaNews.Livescore.Provider.API.Models.Team;

    public class MatchDetailResponse : MatchResponse
    {
        public MatchExtraData ExtraData { get; set; }
        public TeamStatistic HomeTeamStatistic => ExtraData?.ToHomeTeamDetails();
        public TeamStatistic AwayTeamStatistic => ExtraData?.ToAwayTeamDetails();
        public int[] FootballAddStartTime { get; set; }
        public int FirstExtraTime => FootballAddStartTime is not null && FootballAddStartTime.Length > 0 ? FootballAddStartTime[0] : 0;
        public int SeccondExtraTime => FootballAddStartTime is not null && FootballAddStartTime.Length > 0 ? FootballAddStartTime[1] : 0;
    }
}