namespace SabaNews.Models.Posts
{
    using System.Collections.Generic;
    using System.Linq;

    public class PostViewStatisticModel
    {
        public IEnumerable<PostStatisticInfo> PostViewDetails { get; set; }

        public int TotalPost
        {
            get => PostViewDetails.Sum(i => i.PostCount);
        }

        public int TotalVideo
        {
            get => PostViewDetails.Sum(i => i.VideoCount);
        }

        public int TotalPostView
        {
            get => PostViewDetails.Sum(i => i.PostView);
        }

        public int TotalVideoView
        {
            get => PostViewDetails.Sum(i => i.VideoView);
        }
    }
}