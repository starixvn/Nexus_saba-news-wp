namespace sabanews.Models
{
    using System.Collections.Generic;

    public class CategoryViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public Category Category { get; set; }
        public ViewData ViewData { get; set; }
    }
}