using SabaNews.Livescore.Models.Match;
using System.Collections.Generic;

namespace SabaNews.Models.ViewModel.Event.Euro2024
{
    public class TeamViewModel
    {
        public HeaderViewModel Header { get; set; }
        public IEnumerable<SeasonStandings> Standings { get; set; }
    }
}
