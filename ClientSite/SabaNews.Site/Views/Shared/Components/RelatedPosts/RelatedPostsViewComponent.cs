namespace SabaNews.Site.Views.Shared.Components.RelatedPosts
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models.Posts;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class RelatedPostsViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public RelatedPostsViewComponent(IPostService postService)
        {
            this.postService = postService;
        }


        public async Task<IViewComponentResult> InvokeAsync(int postID, string slug = "")
        {
            IEnumerable<Post> posts ;
            if (slug == "")
            {
                var recentParam = new GetRecentPostsParam
                {
                    Start = Constants.FromFirstItem,
                    End = Constants.GetElevenItems,
                    PostID = postID
                };
                posts = await postService.GetRecentPostsAsync(recentParam);
            }
            else 
            {
                var criteria = new GetPostsByCateSlugCriteria
                {
                    _CategorySlug = slug,
                    _Start = Constants.FromFirstItem,
                    _End = Constants.GetElevenItems,
                };
                var relatedParam = new PostParam
                {
                    Slug = slug,
                    Start = Constants.FromFirstItem,
                    End = Constants.GetElevenItems,
                    PostID = postID
                };
                var allPosts = await postService.GetPostsByCateSlugAsync(criteria);
                posts = GetPostsExcerptPostID(allPosts, relatedParam);
            }
            
            return View(posts);
        }

        public static IEnumerable<Post> GetPostsExcerptPostID(IEnumerable<Post> posts, PostParam param)
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
