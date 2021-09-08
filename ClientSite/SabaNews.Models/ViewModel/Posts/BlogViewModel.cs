namespace SabaNews.Models.ViewModel.Posts
{
    using SabaNews.Models.Posts;
    using System.Collections.Generic;

    internal class BlogViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public ViewData ViewData { get; set; }
    }
}