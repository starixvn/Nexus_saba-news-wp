namespace SabaNews.Models.ViewModel.Event.WorldCup
{
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Team;
    using System.Collections.Generic;

    public class PlayerViewModel
    {
        public TopScorers TopScorers { get; set; }
        public IEnumerable<Team> Teams { get; set; }
        public IEnumerable<Player> Players { get; set; }
    }
}
