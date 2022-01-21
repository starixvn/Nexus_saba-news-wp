namespace SabaNews.Models.ViewModel.Comment
{
    using SabaNews.Core.Models;
    using System.Collections.Generic;
    using SabaNews.Models.Comment;

    public class CommentGridViewModel
    {
        public IEnumerable<Comment> Comments { get; set; }

        public PagingModel Paging { get; set; }

        public string DateSort { get; set; }
    }
}
