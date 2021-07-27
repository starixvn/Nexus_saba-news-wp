namespace SabaNews.Models.Email
{
    using SabaNews.Models;
    using System.Collections.Generic;

    public class EmailViewModel
    {
        public IEnumerable<EmailModel> Emails { get; set; }

        public ViewData ViewData { get; set; }

    }
}
