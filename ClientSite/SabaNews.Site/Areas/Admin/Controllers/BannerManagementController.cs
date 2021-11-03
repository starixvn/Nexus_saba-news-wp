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
        private const string DefaultDestopUrl = "images/Desktop_320x70.png";
        private const string DefaultMobileUrl = "images/Mobile_375x200.png";
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
            banner.DesktopUrl = string.IsNullOrEmpty(banner.DesktopUrl) ? string.Concat(baseUrl, DefaultDestopUrl) : banner.DesktopUrl;
            banner.MobileUrl = string.IsNullOrEmpty(banner.MobileUrl) ? string.Concat(baseUrl, DefaultMobileUrl) : banner.MobileUrl;

            return View(banner);
        }

        [HttpPost]
        public async Task<IActionResult> UpdateInfo(string navigationUrl)
        {
            var banner = await bannerService.GetBannerInfoAsync();
            banner.NavigationUrl = navigationUrl;
            var result = await bannerService.UpdateBannerInfoAsync(banner);
            return Json(result);
        }

        [HttpPost]
        public async Task<IActionResult> UploadBanner(IFormFile file, string type)
        {
            var result = new CommandResult();
            try
            {
                if (!Directory.Exists(pathService.GetPathAndFilenameUploadBanner("")))
                {
                    Directory.CreateDirectory(pathService.GetPathAndFilenameUploadBanner(""));
                }
                var banner = await bannerService.GetBannerInfoAsync();
                string filename = ContentDispositionHeaderValue.Parse(file.ContentDisposition).FileName.Trim('"');
                filename = pathService.EnsureCorrectFilename(filename);
                filename = DateTime.Now.Millisecond + '-' + filename;
                var filePath = pathService.GetPathAndFilenameUploadBanner(filename);
                if (System.IO.File.Exists(filePath))
                {
                    System.IO.File.Delete(pathService.GetPathAndFilenameUploadBanner(filePath));
                }
                using (FileStream output = System.IO.File.Create(pathService.GetPathAndFilenameUploadBanner(filename)))
                    await file.CopyToAsync(output);

                string linkPhoto = "uploads/banner/" + filename;
                string url = string.Concat(baseUrl, linkPhoto);
                if (type == BannerType.Desktop)
                {
                    banner.DesktopUrl = url;
                }
                else
                {
                    banner.MobileUrl = url;
                }
                
                result = await bannerService.UpdateBannerInfoAsync(banner);

            }
            catch (Exception e)
            {
                result.Message = e.Message;
            }
            return Json(result);
            
        }
    }
}
