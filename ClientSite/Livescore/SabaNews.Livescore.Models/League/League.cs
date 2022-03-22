namespace SabaNews.Livescore.Models.League
{
    public class League
    {
        public long LeagueId { get; set; }

        public string LeagueNameEN { get; set; }
        public string LeagueFullNameEN { get; set; }

        public string Icon { get; set; }

        public byte LeagugeType { get; set; }

        public short CurrentRound { get; set; }
        public short RoundCount { get; set; }

        public long CurrentStageId { get; set; }
        public string CurrentStageNameEN { get; set; }

        public bool IsMajor { get; set; }
    }
}