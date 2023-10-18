namespace SabaNews.Models.Comment
{
    using System;

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
        public string Photo { get; set; }
    }
}
