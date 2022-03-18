namespace SabaNews.Livescore.Models.Team
{
    using SabaNews.Livescore.Core;

    public class Team : BaseModel
    {
        public bool isHomeTeam { get; set; }

        public string Country { get; set; }

        public string Abbreviation { get; set; }

        public string Logo { get; set; }

        public TeamStatistic Statistics { get; set; }

        public TeamLineups Lineups { get; set; }
    }
}