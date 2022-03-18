namespace SabaNews.Livescore.Models.League
{
    using SabaNews.Livescore.Core;

    public class League : BaseModel
    {
        public bool IsMajor { get; set; }

        public int Level { get; set; }

        public string Icon { get; set; }

        public int LeagugeType { get; set; }

        public int CurrentRound { get; set; }

        public long CategoryId { get; set; }

        public long CurrentStageId { get; set; }

        public string CurrentStageName { get; set; }

        public string Country { get; set; }

        public string City { get; set; }

        public LeagueSession Seasion { get; set; }
    }
}