namespace SabaNews.Models.ViewModel
{
    using SabaNews.Models.Posts;
    using System.Collections.Generic;
    public class Euro2020RankingViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public Post ThirdPlaceTable { get; set; }
        public Category Category { get; set; }
        public ViewData ViewData { get; set; }
    }
}
