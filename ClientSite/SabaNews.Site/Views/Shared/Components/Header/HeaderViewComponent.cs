namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class HeaderViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;
        private readonly ISessionService sessionService;

        public HeaderViewComponent(IDataControlService dataControlService, ISessionService sessionService)
        {
            this.dataControlService = dataControlService;
            this.sessionService = sessionService;
        }

        public async Task<IViewComponentResult> InvokeAsync(string page = "")
        {
            var model = await dataControlService.GetMenuData();
            var userAgent = Request.Headers["User-Agent"].ToString();
            ViewBag.UserUgent = userAgent;
            ViewBag.IsMobile = userAgent.Contains("mobile", System.StringComparison.OrdinalIgnoreCase);
            ViewBag.IsIpad = userAgent.Contains("ipad", System.StringComparison.OrdinalIgnoreCase);
            var isAuthenticated = sessionService.User != null;
            ViewBag.IsAuthenticated = isAuthenticated;
            ViewBag.Page = page;
            if (isAuthenticated)
            {
                ViewBag.DisplayName = sessionService.User.Name;
                ViewBag.Photo = sessionService.User.Photo;
            }
            return View(model);
        }
    }
}