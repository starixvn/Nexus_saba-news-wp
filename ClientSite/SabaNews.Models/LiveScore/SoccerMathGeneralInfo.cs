namespace SabaNews.Models.LiveScore
{
    using System;

    public class SoccerMathGeneralInfo
    {
        public int MatchId { get; set; }
        public int LeagueId { get; set; }
        public string LeagueNameEN { get; set; }
        public string LeagueFullNameEN { get; set; }
        public int HomeTeamId { get; set; }
        public string HomeTeamNameEN { get; set; }
        public string TeamFullNameEN { get; set; }
        public string HomeTeamIcon { get; set; }
        public int AwayTeamId { get; set; }
        public string AwayTeamNameEN { get; set; }
        public string AwayFullNameEN { get; set; }
        public string AwayTeamIcon { get; set; }
        public string Stage { get; set; }
        public int CurrentRound { get; set; }
        public int Round { get; set; }
        public int IsLineUp { get; set; }
        public DateTime StartTime { get; set; }
        public DateTime EndTime { get; set; }
        public int HomeTeamHalfScore { get; set; }
        public int AwayTeamHalfScore { get; set; }
        public int HomeTeamTotalScore { get; set; }
        public int AwayTeamTotalScore { get; set; }
        public int HomeTeamAggregateScore { get; set; }
        public int AwayTeamAggregateScore { get; set; }
        public int Status { get; set; }
    }
}
