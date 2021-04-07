namespace SabaNews.Models
{
    using System;

    public class NewsSitemapModel
    {
        public string Slug { get; set; }

        public string Title { get; set; }

        public DateTime? ModifiedDate { get; set; }

        public string Tags { get; set; }
    }
}
