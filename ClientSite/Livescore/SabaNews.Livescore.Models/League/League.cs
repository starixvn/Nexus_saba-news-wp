namespace SabaNews.Livescore.Models.League
{
    public class League
    {
        public long LeagueId { get; set; }

        public string LeagueNameEN { get; set; }
        public string LeagueFullNameEN { get; set; }

        public string Icon { get; set; }

        public byte LeagueType { get; set; }

        public short CurrentRound { get; set; }
        public short RoundCount { get; set; }

        public bool IsMajor { get; set; }

        public Season Season { get; set; }
        public SeasonStage Stage { get; set; }
    }
}