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
            var posts = (IEnumerable<Post>)null;
            if (string.IsNullOrWhiteSpace(cateSlug))
            {
                var postTemplate = await postService.GetRecentPostsAsync(Constants.VideoSlug, 0, 4);
                posts = postTemplate.Posts;
            }
            else
            {
                posts = await postService.GetTopPostsByCateSlugAsync(cateSlug, 0, 4);
            }
           
            var Model = new SideBarViewModel
            {
                Posts = posts,
            };

            return View(Model);
        }
    }
}