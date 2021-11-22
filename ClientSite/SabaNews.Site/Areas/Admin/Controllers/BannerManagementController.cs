namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
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
            var banner = await bannerService.GetBannerInfoAsync();
            switch (type)
            {
                case BannerType.Navigation:
                    banner.NavigationUrl = value;
                    break;
                case BannerType.Desktop:
                    banner.DesktopUrl = value;
                    break;
                case BannerType.Mobile:
                    banner.MobileUrl = value;
                    break;
                default:
                    throw new InvalidOperationException();
            }
            var result = await bannerService.UpdateBannerInfoAsync(banner);
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
                var filePath = pathService.MapAbsoluteRootPath(Constants.BannerUploadFolder + filename);
                if (System.IO.File.Exists(filePath))
                {
                    System.IO.File.Delete(filePath);
                }
                using (FileStream output = System.IO.File.Create(filePath))
                    await file.CopyToAsync(output);

                string linkPhoto = Constants.BannerUploadFolder + filename;
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
                result = new CommandResult { Message = e.Message }; 
            }
            return Json(result);
        }
    }
}
