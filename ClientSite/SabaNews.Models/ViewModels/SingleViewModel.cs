namespace SabaNews.Models.ViewModel
{
    using SabaNews.Models.Comment;
    using System.Collections.Generic;

    public class SingleViewModel
    {
        public Post SinglePost { get; set; }
        public IEnumerable<Post> RelatedPosts { get; set; }
        public IEnumerable<Post> RecentPosts { get; set; }
        public ViewData ViewData { get; set; }
        public bool IsLogin { get; set; }
        public bool isSave { get; set; }
    }
}
