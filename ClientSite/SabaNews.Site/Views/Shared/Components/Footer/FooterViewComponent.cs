namespace SabaNews.Views.Shared.Components
{
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;

    public class FooterViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;

        public FooterViewComponent(IDataControlService dataControlService)
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