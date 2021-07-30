namespace SabaNews.Core.Models
{
    public class PagingModel
    {
        public int CurrentPage { get; set; }

        public int PageSize { get; set; }

        public int TotalPages { get; set; }

        public string SearchValue { get; set; }
    }
}
