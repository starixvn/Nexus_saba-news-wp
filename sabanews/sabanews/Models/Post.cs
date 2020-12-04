namespace sabanews.Models
{
    public class Post
    {
        public int ID { get; set; }
        public string PostTitle { get; set; }
        public string PostContent {get; set;}
        public string PostExcerpt { get; set; }
        public string PostDate { get; set; }
        public string FeatureImage { get; set; }
        public string Tags { get; set; }
        public string TagsSlug { get; set; }
        public string Categories { get; set; }
        public string Slug { get; set; }
    }
}
