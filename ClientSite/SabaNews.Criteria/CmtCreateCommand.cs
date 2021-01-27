namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;
    using SabaNews.Core.Models;
    using System;

    public class CmtCreateCommand : CriteriaBase
    {
        public int CommentID { get; set; }
        public int CommentPostID { get; set; }
        public string CommentAuthor { get; set; }
        public string CommentAuthorEmail { get; set; }
        public string CommentAuthorIP { get; set; }
        public DateTime CommentDate { get; set; }
        public DateTime CommentDateGMT { get; set; }
        public string CommentContent { get; set; }
        public string CommentAgent { get; set; }
        public int CommentParent { get; set; }
        public int UserID { get; set; }

        public override string GetSettingKey() => "cmt_create";

        public override bool IsValid() => true;
    }
}
