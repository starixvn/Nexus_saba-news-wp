using SabaNews.Core.Models;
using System.Collections.Generic;

namespace SabaNews.Models.ViewModels
{
    public class SavedPostViewModel
    {
        public IEnumerable<Post> Posts { get; set; }

        public SavePost ToDayPost { get; set; }

        public UserSession User { get; set; }

        public string Title { get; set; }

        public string Page { get; set; }

    }
}
