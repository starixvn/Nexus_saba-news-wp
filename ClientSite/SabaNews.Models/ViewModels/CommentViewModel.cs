namespace SabaNews.Models.ViewModels
{
    using SabaNews.Models.Comment;
    public class CommentViewModel
    {
        public CommentTemplate CommentTemplate { get; set; }
        public ViewData ViewData { get; set; }
        public string FilterType { get; set; }
        public bool IsLogin { get; set; }
        public bool GetMore { get; set; }

    }
}
