namespace SabaNews.Models.ViewModels
{
    using SabaNews.Core.Models;
    using SabaNews.Models.Comment;
    public class CommentViewModel
    {
        public CommentTemplate CommentTemplate { get; set; }
        public PagingModel Paging { get; set; }
        public string FilterType { get; set; }
        public bool IsLogin { get; set; }
        public bool GetMore { get; set; }

    }
}
