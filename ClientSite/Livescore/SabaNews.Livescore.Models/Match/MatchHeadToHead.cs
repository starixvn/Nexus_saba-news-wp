namespace SabaNews.Livescore.Models.Match
{
    using System;

    public class MatchHeadToHead
    {
        public long MatchId { get; set; }
        public int HomeHalfScore { get; set; }
        public int AwayHalfScore { get; set; }
        public int HomeTotalScore { get; set; }
        public int AwayTotalScore { get; set; }
        public int HomeTeamId { get; set; }
        public string HomeTeamNameEN { get; set; }
        public string HomeTeamFullNameEN { get; set; }
        public string HomeTeamIcon { get; set; }
        public int AwayTeamId { get; set; }
        public string AwayTeamNameEN { get; set; }
        public string AwayTeamFullNameEN { get; set; }
        public string AwayTeamIcon { get; set; }
        public DateTime EventDate { get; set; }
    }
}
