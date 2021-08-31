using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Models.Posts
{
    public class GetRecentPostsParam
    {
        public string Slug { get; set; }
        public int Start { get; set; }
        public int End { get; set; }
        public int PostID { get; set; }
        public int TotalIItems { get; set; }
    }
}
