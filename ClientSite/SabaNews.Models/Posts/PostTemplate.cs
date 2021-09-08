namespace SabaNews.Models.Posts
{
    using System.Collections.Generic;

    public class PostTemplate
    {
        public int TotalItems { get; set; }
        public IEnumerable<Post> Posts { get; set; }

    }
}
