namespace SabaNews.Site.Views.Event.WorldCup2022.RelatedPosts
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models.Posts;
    using SabaNews.Services;

    public class RelatedPostsWCViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public RelatedPostsWCViewComponent(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IViewComponentResult> InvokeAsync(int postID, string slugWC = "")
        {
            IEnumerable<Post> postsWC ;
            if (slugWC == "")
            {
                var recentWCParam = new GetRecentPostsParam
                {
                    Start = Constants.FromFirstItem,
                    End = Constants.GetElevenItems,
                    PostID = postID
                };
                postsWC = await postService.GetRecentPostsAsync(recentWCParam);
            }
            else 
            {
                var cateSlugCriteria = new GetPostsByCateSlugCriteria
                {
                    _CategorySlug = slugWC,
                    _Start = Constants.FromFirstItem,
                    _End = Constants.GetElevenItems,
                };
                var relatedWCParam = new PostParam
                {
                    Slug = slugWC,
                    Start = Constants.FromFirstItem,
                    End = Constants.GetElevenItems,
                    PostID = postID
                };
                var allPosts = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
                postsWC = GetPostsExcerptPostID(allPosts, relatedWCParam);
            }
            
            return View("~/Views/Event/WorldCup2022/RelatedPosts/Default.cshtml", postsWC);
        }

        private static IEnumerable<Post> GetPostsExcerptPostID(IEnumerable<Post> posts, PostParam param)
        {
            var totalRealItems = param.End - 1;
            var excerptCurrentPost = posts.Where(item => item.ID != param.PostID).ToList();
            posts = excerptCurrentPost;
            if (excerptCurrentPost.Count > totalRealItems)
            {
                posts = excerptCurrentPost.Skip(param.Start).Take(totalRealItems).ToList();
            }
            return posts;
        }
    }
}
