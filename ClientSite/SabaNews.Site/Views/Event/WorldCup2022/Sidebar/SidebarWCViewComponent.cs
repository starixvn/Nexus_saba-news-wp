namespace SabaNews.Site.Views.Event.WorldCup2022.Sidebar
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;

    public class SidebarWCViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public SidebarWCViewComponent(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IViewComponentResult> InvokeAsync(string cateSlugWC = "", int postID = 0)
        {
            IEnumerable<Post> postsWC;
            if (string.IsNullOrWhiteSpace(cateSlugWC))
            {
                var endItemsWC = postID == 0 ? Constants.GetFourItems : Constants.GetFiveItems;
                var postWCParam = new GetRecentPostsParam
                {
                    Start = Constants.FromFirstItem,
                    End = endItemsWC,
                    PostID = postID
                };
                postsWC = await postService.GetRecentPostsAsync(postWCParam);
                ViewData[Constants.ViewDataHeadTitle] = Constants.RecentPostsTitle;
            }
            else
            {
                var endItemsWC = postID == 0 ? Constants.GetFourItems : Constants.GetFiveItems;
                var postWCParam = new PostParam
                {
                    Slug = cateSlugWC,
                    Start = Constants.FromFirstItem,
                    End = endItemsWC,
                    PostID = postID
                };
                postsWC = await postService.GetTopPostsByCateSlugAsync(postWCParam);
                
                ViewData[Constants.ViewDataHeadTitle] = Constants.MostViewPostTitle;
            }
           
            var viewModel = new SideBarViewModel
            {
                Posts = postsWC,
            };

            return View("~/Views/Event/WorldCup2022/Sidebar/Default.cshtml", viewModel);
        }
    }
}