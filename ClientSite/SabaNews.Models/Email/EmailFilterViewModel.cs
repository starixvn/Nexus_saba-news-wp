namespace SabaNews.Models.Email
{
    using System;

    public class EmailFilterViewModel
    {
        public int Page { get; set; }

        public string Search { get; set; }

        public DateTime FromDate { get; set; }

        public DateTime ToDate { get; set; }
    }
}
