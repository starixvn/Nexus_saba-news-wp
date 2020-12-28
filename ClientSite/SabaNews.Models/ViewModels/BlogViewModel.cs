namespace sabanews.Models
{
    using System.Collections.Generic;

    internal class BlogViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public ViewData ViewData { get; set; }
    }
}