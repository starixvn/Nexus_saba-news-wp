namespace SabaNews.Models.Posts
{
    using SabaNews.Models.ViewModel.Posts;

    public class GetDetailContent
    {
        public int PostId { get; set; }
        public string Title { get; set; }
        public string PublishDate { get; set; }
        public Subjects Subjects { get; set; }
        public int EditorId { get; set; }
        public string Content { get; set; }
        public Tags Tags { get; set; }
        public int Category { get; set; }
        public int LikeCount { get; set; }
        public string UrlSlug { get; set; }
        public string SabaVideoContent { get; set; }
    }
}
