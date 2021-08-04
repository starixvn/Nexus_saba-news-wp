namespace SabaNews.Site.Views.Shared.Components
{
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;

    public class HeaderEventViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;
        private readonly ISessionService sessionService;

        public HeaderEventViewComponent(IDataControlService dataControlService, ISessionService sessionService)
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
            ViewBag.IsAdmin = sessionService.User != null && sessionService.User.Metadata.Contains(Role.Administrator);
            if (isAuthenticated)
            {
                ViewBag.DisplayName = sessionService.User.Name;
                ViewBag.Photo = sessionService.User.Photo;
            }
            return View(model);
        }
    }
}