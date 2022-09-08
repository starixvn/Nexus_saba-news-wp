using SabaNews.Core.Models;
using SabaNews.Livescore.Models.League;
using System.Collections.Generic;

namespace SabaNews.Models.ViewModel.Event.WorldCup
{
    public class MinigameGridViewModel
    {
        public IEnumerable<MiniGame> MiniGames { get; set; }
        public PagingModel Paging { get; set; }
    }
}
