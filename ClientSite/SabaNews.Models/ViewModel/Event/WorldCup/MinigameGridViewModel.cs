using SabaNews.Core.Models;
using SabaNews.Livescore.Models.League;
using SabaNews.Models.Event.WorldCup;
using System.Collections.Generic;

namespace SabaNews.Models.ViewModel.Event.WorldCup
{
    public class MinigameGridViewModel
    {
        public IList<UserMinigameModel> MiniGames { get; set; }
        public PagingModel Paging { get; set; }
    }
}
