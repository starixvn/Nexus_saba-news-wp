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

        public async Task<IViewComponentResult> InvokeAsync(string cateSlug = "")
        {
            IEnumerable<Post> posts;
            if (string.IsNullOrWhiteSpace(cateSlug))
            {
                var postTemplate = await postService.GetRecentPostsAsync(Constants.VideoSlug, Constants.FromFirstItem, Constants.GetFourItems);
                posts = postTemplate.Posts;
                ViewData[Constants.ViewDataHeadTitle] = Constants.RecentPostsTitle;
            }
            else
            {
                posts = await postService.GetTopPostsByCateSlugAsync(cateSlug, Constants.FromFirstItem, Constants.GetFourItems);
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