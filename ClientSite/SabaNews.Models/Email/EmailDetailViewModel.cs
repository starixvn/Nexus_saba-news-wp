using System.Collections.Generic;

namespace SabaNews.Models.Email
{
    public class EmailDetailViewModel
    {

        public IEnumerable<EmailDetailModel> ListEmailDetails { get; set; }

        public ViewData ViewData { get; set; }
    }
}
