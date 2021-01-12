namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class MenuViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;

        public MenuViewComponent(IDataControlService dataControlService)
        {
            this.dataControlService = dataControlService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var model = await dataControlService.GetMenuData();
            var userAgent = Request.Headers["User-Agent"].ToString();
            ViewBag.UserUgent = userAgent;
            ViewBag.IsMobile = userAgent.Contains("mobile", System.StringComparison.OrdinalIgnoreCase);
            ViewBag.IsIpad = userAgent.Contains("ipad", System.StringComparison.OrdinalIgnoreCase);
            return View(model);
        }
    }
}