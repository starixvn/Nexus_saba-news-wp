namespace SabaNews.Models.ViewModel
{
    using System.Collections.Generic;

    public class SingleViewModel
    {
        public Post SinglePost { get; set; }
        public IEnumerable<Post> RelatedPosts { get; set; }
        public IEnumerable<Post> RecentPosts { get; set; }
    }
}
