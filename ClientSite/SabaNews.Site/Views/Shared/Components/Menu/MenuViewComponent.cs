namespace sabanews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Constants;
    using sabanews.Services.Interfaces;
    using System.Threading.Tasks;

    public class MenuViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public MenuViewComponent(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var model = await postService.GetMenuAsync("Main Menu");
            return View(model);
        }
    }
}