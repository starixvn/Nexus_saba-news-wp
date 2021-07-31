namespace SabaNews.Models
{
    using SabaNews.Core.Models;
    using System.Collections.Generic;

    public class MenuViewModel
    {
        public IEnumerable<Menu> Menus { get; set; }
        public UserSession User { get; set; }
        public string UserUgent { get; set; }
        public bool IsAuthenticated { get; set; }
        public string Page { get; set; }
        public string CategorySlug { get; set; }
    }
}
