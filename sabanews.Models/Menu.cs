namespace sabanews.Models
{
    public class Menu
    {
        public int ID { get; set; }
        public string Name { get; set; }
        public int CatId { get; set; }
        public int ParentId { get; set; }
        public string Slug { get; set; }
        public string Title { get; set; }
    }
}
