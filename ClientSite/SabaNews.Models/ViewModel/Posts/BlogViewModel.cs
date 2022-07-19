namespace SabaNews.Models.ViewModel.Posts
{
    using SabaNews.Core.Models;
    using SabaNews.Models.Posts;
    using System.Collections.Generic;
    using System.Linq;

    public class BlogViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public Category Category { get; set; }
        public HeaderViewModel Header { get; set; }
        public PagingModel Paging { get; set; }
        public IEnumerable<Menu> Menus { get; set; }
        public Slugs Slugs { get; set; }

        public IEnumerable<Menu> GetSubMenu(string cateSlug) {
            var menu = new List<Menu>();
            if (Menus.Any() && cateSlug != null)
            {
                var menuItem = GetMenuDetail(cateSlug);
                menu = Menus.Where(item => item.ParentId == menuItem.PostId).ToList();
            }
            return menu;
        }

        public Menu GetMenuDetail(string cateSlug) {
            return Menus.FirstOrDefault(item => item.CateSlug == cateSlug);
        }

        public IEnumerable<Menu> GetCoMenu(string cateSlug)
        {
            var menu = new List<Menu>();
            if (Menus.Any() && cateSlug != null)
            {
                var menuItem = GetMenuDetail(cateSlug);
                menu = Menus.Where(item => item.ParentId == menuItem.ParentId).ToList();
            }
            return menu;
        }
    }
}
