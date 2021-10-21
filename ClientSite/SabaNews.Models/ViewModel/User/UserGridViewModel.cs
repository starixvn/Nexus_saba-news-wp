namespace SabaNews.Models.ViewModel.User
{
    using SabaNews.Core.Models;
    using System.Collections.Generic;

    public class UserGridViewModel
    {
        public IEnumerable<UserSession> Users { get; set; }

        public string DateSort { get; set; }

        public int TotalItems { get; set; }

        public PagingModel Paging { get; set; }
    }
}
