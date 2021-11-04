namespace SabaNews.Views.Shared.Components
{
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Services.Interfaces;

    public class MenuViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;
        private readonly ISessionService sessionService;
        private readonly IBannerService bannerService;

        public MenuViewComponent(IDataControlService dataControlService, ISessionService sessionService, IBannerService bannerService)
        {
            this.dataControlService = dataControlService;
            this.sessionService = sessionService;
            this.bannerService = bannerService;
        }

        public async Task<IViewComponentResult> InvokeAsync(string categorySlug = "", string page = "")
        {
            var menus = await dataControlService.GetMenuData();
            var isAuthenticated = sessionService.User != null;
            var model = new MenuViewModel
            {
                Menus = menus,
                Page = page,
                IsAuthenticated = isAuthenticated,
                CategorySlug = categorySlug,
                IsAdmin = isAuthenticated && !string.IsNullOrWhiteSpace(sessionService.User.Metadata)
                        && sessionService.User.Metadata.Contains(Role.Administrator),
                Banner = await bannerService.GetBannerInfoAsync()
            };
            if (isAuthenticated)
            {
                model.User = sessionService.User;
            }
            return View(model);
        }
    }
}