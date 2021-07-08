using System;

namespace SabaNews.Models.Email
{
    public class EmailModel
    {
        public string EmailTitle { get; set; }

        public string EmailContent { get; set; }

        public string EmailReturnLink { get; set; }

        public int CreatedBy { get; set; }

        public DateTime SendDate { get; set; }

        public int EmailId { get; set; }
    }
}
