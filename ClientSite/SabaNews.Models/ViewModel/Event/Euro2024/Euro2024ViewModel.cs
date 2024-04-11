using SabaNews.Models.Posts;
using System.Collections.Generic;


namespace SabaNews.Models.ViewModel.Event.Euro2024
{
    public class Euro2024ViewModel
    {
        public IEnumerable<Post> LastestNews { get; set; }
        public IEnumerable<Post> Commentary { get; set; }
        public IEnumerable<Post> Videos { get; set; }
        public IEnumerable<Post> StoryPost { get; set; }
        public Category Category { get; set; }
        public HeaderViewModel Header { get; set; }
        public string OverviewPageUrl { get; set; }
    }
}
