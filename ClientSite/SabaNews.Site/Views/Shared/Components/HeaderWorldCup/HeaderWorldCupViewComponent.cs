namespace SabaNews.Site.Views.Shared.Components.HeaderWorldCup
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;

    public class HeaderWorldCupViewComponent : ViewComponent
    {
        private readonly ISessionService sessionService;

        public HeaderWorldCupViewComponent(ISessionService sessionService)
        {
            this.sessionService = sessionService;
        }

        public IViewComponentResult Invoke(string cateSlug = "")
        {
            var isAuthenticated = sessionService.User != null;
            var model = new TopHeaderViewModel
            {
                CateSlug = cateSlug,
                IsAuthenticated = isAuthenticated,
                IsAdmin = isAuthenticated && sessionService.User.Metadata.Contains(Role.Administrator)
            };
            if (isAuthenticated)
            {
                model.User = sessionService.User;
            }
            return View(model);
        }
    }
}
