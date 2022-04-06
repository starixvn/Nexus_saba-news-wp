namespace SabaNews.Models.ViewModel.User
{
    using System;

    public class UserFilterViewModel
    {
        public DateTime FromDate { get; set; }

        public DateTime ToDate { get; set; }

        public string DateSort { get; set; }

        public string Search { get; set; }

        public int PageSize { get; set; }

        public int Page { get; set; } 

    }
}
