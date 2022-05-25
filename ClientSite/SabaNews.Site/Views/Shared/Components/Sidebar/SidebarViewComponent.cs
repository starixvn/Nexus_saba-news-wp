namespace SabaNews.Views.Shared.Components
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;

    public class SidebarViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public SidebarViewComponent(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IViewComponentResult> InvokeAsync(string cateSlug = "", int postID = 0)
        {
            IEnumerable<Post> posts;
            if (string.IsNullOrWhiteSpace(cateSlug))
            {
                var end = postID == 0 ? Constants.GetFourItems : Constants.GetFiveItems;
                var postParam = new GetRecentPostsParam
                {
                    Start = Constants.FromFirstItem,
                    End = end,
                    PostID = postID
                };
                posts = await postService.GetRecentPostsAsync(postParam);
                ViewData[Constants.ViewDataHeadTitle] = Constants.RecentPostsTitle;
            }
            else
            {
                var end = postID == 0 ? Constants.GetFourItems : Constants.GetFiveItems;
                var postParam = new PostParam
                {
                    Slug = cateSlug,
                    Start = Constants.FromFirstItem,
                    End = end,
                    PostID = postID
                };
                posts = await postService.GetTopPostsByCateSlugAsync(postParam);
                
                ViewData[Constants.ViewDataHeadTitle] = Constants.MostViewPostTitle;
            }
           
            var Model = new SideBarViewModel
            {
                Posts = posts,
            };

            return View(Model);
        }
    }
}