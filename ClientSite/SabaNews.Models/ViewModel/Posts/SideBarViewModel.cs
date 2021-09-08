namespace SabaNews.Models.ViewModel.Posts
{
    using SabaNews.Models.Posts;
    using System.Collections.Generic;
    public class SideBarViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public int Days { get; set; }
        public int CurrentMonth { get; set; }
        public int CurrentYear { get; set; }
        public int CurrentDay { get; set; }
        public int FirstDayOfWeek { get; set; }
    }
}