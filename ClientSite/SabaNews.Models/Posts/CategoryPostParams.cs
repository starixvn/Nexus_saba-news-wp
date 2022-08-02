namespace SabaNews.Models.Posts
{
    public class CategoryPostParams
    {

        public Slugs Slugs { get; set; }

        public int Page { get; set; }

        public int PageSize { get; set; }

        public bool IsVideo { get; set; }
    }
}
