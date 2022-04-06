namespace SabaNews.Models.ViewModel
{
    using SabaNews.Models.Posts;
    using System.Collections.Generic;

    public class Euro2020ViewModel
    {
        public IEnumerable<Post> HotNews { get; set; }
        public IEnumerable<Post> Videos { get; set; }
        public Post TotalRanking { get; set; }
        public IEnumerable<Post> Ranking { get; set; }
        public IEnumerable<Post> Identify { get; set; }
        public Category Category { get; set; }
        public ViewData ViewData { get; set; }
    }
}
