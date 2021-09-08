namespace SabaNews.Models.ViewModel.Posts
{
    using SabaNews.Models.Posts;
    using System.Collections.Generic;

    public class CategoryViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public Category Category { get; set; }
        public ViewData ViewData { get; set; }
    }
}