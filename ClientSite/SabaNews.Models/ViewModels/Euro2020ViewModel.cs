using System.Collections.Generic;

namespace SabaNews.Models.ViewModels
{
    public class Euro2020ViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public Post Ranking { get; set; }
        public Category Category { get; set; }
        public ViewData ViewData { get; set; }
    }
}
