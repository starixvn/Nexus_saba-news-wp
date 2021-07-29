using System.Collections.Generic;

namespace SabaNews.Models.Email
{
    public class EmailDetailViewModel
    {

        public IEnumerable<EmailDetailModel> ListEmailDetails { get; set; }

        public EmailModel Email { get; set; }

        public ViewData ViewData { get; set; }
    }
}
