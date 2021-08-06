namespace SabaNews.Site.Views.Shared.Components
{
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
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

        public async Task<IViewComponentResult> InvokeAsync(string page = "")
        {
            var menus = await dataControlService.GetMenuData();
            var isAuthenticated = sessionService.User != null;
            var model = new MenuViewModel
            {
                Menus = menus,
                Page = page,
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