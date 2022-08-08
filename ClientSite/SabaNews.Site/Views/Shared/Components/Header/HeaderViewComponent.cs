
namespace SabaNews.Site.Views.Shared.Components.Header
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
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

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var menus = await dataControlService.GetMenuData();
            var isAuthenticated = sessionService.User != null;
            var model = new TopHeaderViewModel
            {
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
