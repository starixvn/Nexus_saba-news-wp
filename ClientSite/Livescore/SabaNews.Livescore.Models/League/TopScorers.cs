namespace SabaNews.Livescore.Models.League
{
    public class TopScorers
    {
        public long LeagueId { get; set; }
        public long SeasonId { get; set; }
        public long PlayerId { get; set; }
        public long TeamId { get; set; }
        public long Goals { get; set; }
        public long Assists { get; set; }
        public long RowNum { get; set; }
        public long Top { get; set; }
        public string TeamLogo { get; set; }
        public string PlayerNameEN { get; set; }
        public string PlayerFullNameEN { get; set; }

    }
}
