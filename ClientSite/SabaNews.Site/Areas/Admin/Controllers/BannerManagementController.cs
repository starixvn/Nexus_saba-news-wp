namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Threading.Tasks;

    public class BannerManagementController : AdminController
    {
        private readonly IBannerService bannerService;
        private readonly ISessionService sessionService;
        private readonly IFileService fileService;


        public BannerManagementController(
            IBannerService bannerService,
            ISessionService sessionService,
            IFileService fileService)
        {
            this.bannerService = bannerService;
            this.sessionService = sessionService;
            this.fileService = fileService;
        }
        public async Task<IActionResult> Index()
        {
            var banner = await bannerService.GetBannerInfoAsync();

            return View(banner);
        }

        [HttpPost]
        public async Task<IActionResult> UpdateInfo(string value, string type)
        {
            value ??= "";
            var banner = await bannerService.GetBannerInfoAsync();
            var bannerUpdatedData = UpdateBannerData(banner, type, value);
            var result = await bannerService.UpdateBannerInfoAsync(bannerUpdatedData, sessionService.User.Id);
            return Json(result);
        }

        [HttpPost]
        public async Task<IActionResult> UploadBanner(IFormFile file, string type)
        {
            var banner = await bannerService.GetBannerInfoAsync();
            var result = await fileService.UploadFileAsync(file, UploadDir.BannerUploadFolder);
            if (!result.IsSuccessful)
            {
                return Json(result);
            }
            var url = result.Message;
            switch (type)
            {
                case BannerType.DesktopTop:
                    banner.DesktopTopUrl = url;
                    break;
                case BannerType.MobileTop:
                    banner.MobileTopUrl = url;
                    break;
                case BannerType.DesktopCenter:
                    banner.DesktopCenterUrl = url;
                    break;
                case BannerType.DesktopLeft:
                    banner.DesktopLeftUrl = url;
                    break;
                case BannerType.DesktopRight:
                    banner.DesktopRightUrl = url;
                    break;
                case BannerType.MobileCenter:
                    banner.MobileCenterUrl = url;
                    break;
                case BannerType.MobileBottom:
                    banner.MobileBottomUrl = url;
                    break;
                default:
                    throw new InvalidOperationException();
            }
            result = await bannerService.UpdateBannerInfoAsync(banner, sessionService.User.Id);
           
            return Json(result);
        }

        private static BannerModel UpdateBannerData(BannerModel banner, string type, string value)
        {
            switch (type)
            {
                case BannerType.DesktopTopNavigation:
                    banner.DesktopTopNavigationUrl = value;
                    break;
                case BannerType.DesktopTop:
                    banner.DesktopTopUrl = value;
                    break;
                case BannerType.DesktopCenter:
                    banner.DesktopCenterUrl = value;
                    break;
                case BannerType.DesktopCenterNavigation:
                    banner.DesktopCenterNavigationUrl = value;
                    break;
                case BannerType.DesktopLeft:
                    banner.DesktopLeftUrl = value;
                    break;
                case BannerType.DesktopLeftNavigation:
                    banner.DesktopLeftNavigationUrl = value;
                    break;
                case BannerType.DesktopRight:
                    banner.DesktopRightUrl = value;
                    break;
                case BannerType.DesktopRightNavigation:
                    banner.DesktopRightNavigationUrl = value;
                    break;
                default:
                    banner = UpdateBannerMobileData(banner, type, value);
                    break;
            }
            return banner;
        }

        private static BannerModel UpdateBannerMobileData(BannerModel banner, string type, string value)
        {
            switch (type)
            {
                case BannerType.MobileTopNavigation:
                    banner.MobileTopNavigationUrl = value;
                    break;
                case BannerType.MobileTop:
                    banner.MobileTopUrl = value;
                    break;
                case BannerType.MobileCenter:
                    banner.MobileCenterUrl = value;
                    break;
                case BannerType.MobileCenterNavigation:
                    banner.MobileCenterNavigationUrl = value;
                    break;
                case BannerType.MobileBottom:
                    banner.MobileBottomUrl = value;
                    break;
                case BannerType.MobileBottomNavigation:
                    banner.MobileBottomNavigationUrl = value;
                    break;
                default:
                    throw new InvalidOperationException();
            }
            return banner;
        }
    }
}
