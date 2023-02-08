namespace SabaNews.Views.Shared.Components
{
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;

    public class FooterViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;
        private readonly ISessionService sessionService;

        public FooterViewComponent(IDataControlService dataControlService, ISessionService sessionService)
        {
            this.dataControlService = dataControlService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var menus = await dataControlService.GetMenuData();
            return View(menus);
        }
    }
}