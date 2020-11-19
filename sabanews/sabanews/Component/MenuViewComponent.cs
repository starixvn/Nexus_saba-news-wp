namespace sabanews.Component
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Services;
    using sabanews.Constants;
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
            ViewData["MatchSchedule"] = Constants.MatchScheduleCategory;
            ViewData["Home"] = Constants.Home;
            return View(model);
        }
    }
}
