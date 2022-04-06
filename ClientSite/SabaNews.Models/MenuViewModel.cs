namespace SabaNews.Models
{
    using System.Collections.Generic;
    using SabaNews.Core.Models;
    using SabaNews.Models.Banner;

    public class MenuViewModel
    {
        public IEnumerable<Menu> Menus { get; set; }
        public UserSession User { get; set; }
        public BannerModel Banner { get; set; }
        public string UserUgent { get; set; }
        public bool IsAuthenticated { get; set; }
        public string Page { get; set; }
        public string CategorySlug { get; set; }
        public bool IsAdmin { get; set; }
    }
}