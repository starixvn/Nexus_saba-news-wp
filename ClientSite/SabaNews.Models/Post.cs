using System;

namespace sabanews.Models
{
    public class Post
    {
        public int ID { get; set; }
        public string PostTitle { get; set; }
        public string PostContent {get; set;}
        public string PostExcerpt { get; set; }
        public string PostDate { get; set; }
        public string PostDateGMT { get; set; }
        public string FeatureImage { get; set; }
        public string Tags { get; set; }
        public string TagsSlug { get; set; }
        public string Categories { get; set; }
        public string CategoriesSlug { get; set; }
        public string Slug { get; set; }
        public string Type { get; set; }
        public string Metadata { get; set; }
        public string PostTime
        {
            get
            {
                var timeSpan = DateTime.UtcNow - DateTime.Parse(this.PostDateGMT);
                
                if (timeSpan <= TimeSpan.FromSeconds(60))
                {
                    return string.Format("{0} giây trước", timeSpan.Seconds);
                }
                if (timeSpan <= TimeSpan.FromMinutes(60))
                {
                    return timeSpan.Minutes > 1 ? string.Format("{0} phút trước", timeSpan.Minutes) : "1 phút trước";
                }
                if (timeSpan <= TimeSpan.FromHours(24))
                {
                    return timeSpan.Hours > 1 ? string.Format("{0} giờ trước", timeSpan.Hours) : "1 giờ trước";
                }
                var postDate = DateTime.Parse(this.PostDate);
                return postDate.ToString("H:mm dd/MM/yyyy");
            }
        }
    }
}
