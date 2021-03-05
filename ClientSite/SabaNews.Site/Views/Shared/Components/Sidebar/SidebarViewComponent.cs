namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

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
                var postTemplate = await postService.GetRecentPostsAsync(Constants.VideoSlug, Constants.FromFirstItem, end, postID);
                posts = postTemplate.Posts;
                ViewData[Constants.ViewDataHeadTitle] = Constants.RecentPostsTitle;
            }
            else
            {
                var end = postID == 0 ? Constants.GetFourItems : Constants.GetFiveItems;
                posts = await postService.GetTopPostsByCateSlugAsync(cateSlug, Constants.FromFirstItem, end, postID);
                
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