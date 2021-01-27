using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Models.Comment
{
    public class CreateComment
    {
        public long CommentPostID { get; set; }
        public DateTime CommentDateGMT { get; set; }
        public string CommentContent { get; set; }
        public string CommentAuthor { get; set; }
        public string CommentAuthorEmail { get; set; }
        public string CommentAuthorIP { get; set; }
        public DateTime CommentDate { get; set; }
        public string CommentAgent { get; set; }
        public long CommentParent { get; set; }
        public long UserID { get; set; }
        public long CommentID { get; set; }
    }
}
