namespace SabaNews.Models.ViewModel
{
    using System.Collections.Generic;

    public class PostViewModel
    {
        public IEnumerable<Post> NewPosts { get; set; }
        public IEnumerable<Post> FootballPosts { get; set; }
        public IEnumerable<Post> OtherPosts { get; set; }
        public IEnumerable<Post> TennisPosts { get; set; }
        public IEnumerable<Post> VideoPosts { get; set; }
        public IEnumerable<Post> GuessPosts { get; set; }
        public IEnumerable<Post> BackstagePosts { get; set; }
        public IEnumerable<Post> TransferPosts { get; set; }
        public Category Category { get; set; }
    }
}