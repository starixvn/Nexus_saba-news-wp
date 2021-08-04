namespace SabaNews.Models.Email
{
    using System;

    public class EmailFilterViewModel
    {
        public int Page { get; set; }

        public string Search { get; set; }

        public DateTime DateFrom { get; set; }

        public DateTime DateTo { get; set; }
    }
}
