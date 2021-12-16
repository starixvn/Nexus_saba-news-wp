namespace SabaNews.Site.Views.Shared.Components.BannerSideBar
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class BannerSidebarViewComponent : ViewComponent
    {
        private readonly IBannerService bannerService;

        public BannerSidebarViewComponent(IBannerService bannerService)
        {
            this.bannerService = bannerService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var banner = await bannerService.GetBannerInfoAsync();
            return View(banner);
        }
    }
}
