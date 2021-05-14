namespace SabaNews.Models
{
    public class PostParam
    {
        public string Slug  { get; set; }
        public int Start { get; set; }
        public int End { get; set; }
        public int PostID { get; set; } = 0;
        public int TotalIItems { get; set; }
    }
}
