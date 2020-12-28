namespace sabanews.Models
{
    public class Category
    {
        public int CatId { get; set; }
        public string Name { get; set; }
        public int ParentId { get; set; }
        public string Slug { get; set; }
        public string Title { get; set; }
        public string Description { get; set; }
        public string OgImage { get; set; }
        public string OgDescription { get; set; }
        public string OgTitle { get; set; }
    }
}