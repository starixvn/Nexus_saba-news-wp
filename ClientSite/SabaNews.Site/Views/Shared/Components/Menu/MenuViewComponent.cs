namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;
    using SabaNews.Models;

    public class MenuViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public MenuViewComponent(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var model = await postService.GetMenuAsync("New Site Menu");
            return View(model);
        }
    }
}