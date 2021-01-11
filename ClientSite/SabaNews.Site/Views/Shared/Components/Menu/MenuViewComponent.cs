namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;
    using SabaNews.Models;

    public class MenuViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;

        public MenuViewComponent(IDataControlService dataControlService)
        {
            this.dataControlService = dataControlService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var model = await dataControlService.GetMenuData();
            return View(model);
        }
    }
}