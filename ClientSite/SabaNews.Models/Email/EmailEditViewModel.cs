using SabaNews.Core.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Models.Email
{
    public class EmailEditViewModel
    {
        public EmailModel Email { get; set; }

        public string RawUrl { get; set; }

        public string OriginalUrl { get; set; }

        public IEnumerable<UserSession> Users { get; set; }
    }
}
