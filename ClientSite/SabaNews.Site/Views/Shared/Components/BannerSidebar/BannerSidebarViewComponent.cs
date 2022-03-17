namespace SabaNews.Site.Views.Shared.Components.BannerSideBar
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class BannerSidebarViewComponent : ViewComponent
    {
        private readonly IDataControlService dataControlService;

        public BannerSidebarViewComponent(IDataControlService dataControlService)
        {
            this.dataControlService = dataControlService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var banner = await dataControlService.GetBannerData();
            return View(banner);
        }
    }
}
