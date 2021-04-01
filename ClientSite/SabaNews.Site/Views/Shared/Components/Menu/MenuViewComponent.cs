namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class MenuViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;
        private readonly ISessionService sessionService;

        public MenuViewComponent(IDataControlService dataControlService, ISessionService sessionService)
        {
            this.dataControlService = dataControlService;
            this.sessionService = sessionService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var model = await dataControlService.GetMenuData();
            var userAgent = Request.Headers["User-Agent"].ToString();
            ViewBag.UserUgent = userAgent;
            ViewBag.IsMobile = userAgent.Contains("mobile", System.StringComparison.OrdinalIgnoreCase);
            ViewBag.IsIpad = userAgent.Contains("ipad", System.StringComparison.OrdinalIgnoreCase);
            var isAuthenticated = sessionService.User != null;
            ViewBag.IsAuthenticated = isAuthenticated;
            if (isAuthenticated)
            {
                ViewBag.DisplayName = sessionService.User.Name;
                ViewBag.Photo = sessionService.User.Photo;
            }
            return View(model);
        }
    }
}