namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Services;
    using SabaNews.Models;
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

        public async Task<IViewComponentResult> InvokeAsync(string categorySlug = "", string page = "")
        {
            var menus = await dataControlService.GetMenuData();
            var isAuthenticated = sessionService.User != null;
            var model = new MenuViewModel
            {
                Menus = menus,
                Page = page,
                IsAuthenticated = isAuthenticated,
                CategorySlug = categorySlug
            };
            if (isAuthenticated)
            {
                model.User = sessionService.User;
            }
            return View(model);
        }
    }
}