using SabaNews.Core.Models;
using SabaNews.Livescore.Models.League;
using SabaNews.Livescore.Models.Match;
using SabaNews.Models.Posts;
using System.Collections.Generic;

namespace SabaNews.Models.ViewModel.WorldCup2022
{
    public class LastestNewsViewModel
    {
        public IEnumerable<TopScorers> TopScorers { get; set; }
        public IEnumerable<StandingViewModel> Standings { get; set; }
        public IEnumerable<Post> Posts { get; set; }
        public PagingModel Paging { get; set; }
    }
}
