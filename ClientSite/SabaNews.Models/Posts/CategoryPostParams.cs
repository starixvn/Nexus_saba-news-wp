namespace SabaNews.Models.Posts
{
    public class CategoryPostParams
    {

        public string Slug { get; set; }
        public int Page { get; set; }

        public int PageSize { get; set; }

        public bool IsVideo { get; set; }
    }
}
