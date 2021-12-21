namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using System;
    using System.IO;
    using System.Net.Http.Headers;
    using System.Threading.Tasks;

    public class BannerManagementController : AdminController
    {
        private readonly IBannerService bannerService;
        private readonly IPathService pathService;
        private readonly IConfiguration configuration;
        private readonly string baseUrl;
        
        public BannerManagementController(
            IBannerService bannerService,
            IPathService pathService,
            IConfiguration configuration)
        {
            this.bannerService = bannerService;
            this.pathService = pathService;
            this.configuration = configuration;
            baseUrl = configuration.GetValue<string>("BaseUrl");
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
            var result = await bannerService.UpdateBannerInfoAsync(bannerUpdatedData);
            return Json(result);
        }

        [HttpPost]
        public async Task<IActionResult> UploadBanner(IFormFile file, string type)
        {
            CommandResult result;
            try
            {
                var bannerDir = pathService.MapAbsoluteRootPath(Constants.BannerUploadFolder);
                if (!Directory.Exists(bannerDir))
                {
                    Directory.CreateDirectory(bannerDir);
                }
                var banner = await bannerService.GetBannerInfoAsync();
                string filename = ContentDispositionHeaderValue.Parse(file.ContentDisposition).FileName.Trim('"');
                filename = pathService.EnsureCorrectFilename(filename);
                filename = DateTime.Now.Millisecond + filename;
                var filePath = pathService.MapAbsoluteRootPath("/" + Constants.BannerUploadFolder + filename);
                if (System.IO.File.Exists(filePath))
                {
                    System.IO.File.Delete(filePath);
                }
                using (FileStream output = System.IO.File.Create(filePath))
                    await file.CopyToAsync(output);

                string linkPhoto = Constants.BannerUploadFolder + filename;
                string url = string.Concat(baseUrl, linkPhoto);
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
                result = await bannerService.UpdateBannerInfoAsync(banner);
            }
            catch (Exception e)
            {
                result = new CommandResult { Message = e.Message }; 
            }
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
