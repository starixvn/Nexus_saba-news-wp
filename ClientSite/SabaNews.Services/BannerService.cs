namespace SabaNews.Services
{
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Banner;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Threading.Tasks;

    public class BannerService : IBannerService
    {
        private readonly IBaseService baseService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly string baseUrl;
        private const string DefaultDestopUrl = "images/Desktop_320x70.png";
        private const string DefaultMobileUrl = "images/Mobile_375x200.png";

        public BannerService(IBaseService baseService, IConfiguration configuration, ISessionService sessionService)
        {
            this.baseService = baseService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            baseUrl = configuration.GetValue<string>("BaseUrl");
        }
        public async Task<BannerModel> GetBannerInfoAsync()
        {
            var banner =  await baseService.GetDataEntityAsync<BannerModel>(new GetBannerInfoCriteria());
            banner.DesktopTopUrl = string.IsNullOrEmpty(banner.DesktopTopUrl) ? string.Concat(baseUrl, DefaultDestopUrl) : banner.DesktopTopUrl;
            banner.MobileTopUrl = string.IsNullOrEmpty(banner.MobileTopUrl) ? string.Concat(baseUrl, DefaultMobileUrl) : banner.MobileTopUrl;
            return banner;

        }

        public async Task<CommandResult> UpdateBannerInfoAsync(BannerModel banner)
        {
            var criteria = new UpdateBannerInfoCommand
            {
                DesktopTopNavigationUrl = banner.DesktopTopNavigationUrl,
                DesktopTopUrl = banner.DesktopTopUrl,
                MobileTopUrl = banner.MobileTopUrl,
                MobileTopNavigationUrl = banner.MobileTopNavigationUrl,
                DesktopCenterUrl = banner.DesktopCenterUrl,
                DesktopCenterNavigationUrl = banner.DesktopCenterNavigationUrl,
                DesktopLeftUrl = banner.DesktopLeftUrl,
                DesktopLeftNavigationUrl = banner.DesktopLeftNavigationUrl,
                DesktopRightUrl = banner.DesktopRightUrl,
                DesktopRightNavigationUrl = banner.DesktopRightNavigationUrl,
                MobileCenterNavigationUrl = banner.MobileCenterNavigationUrl,
                MobileCenterUrl = banner.MobileCenterUrl,
                ActionId = LogAction.UpdateBanner,
                UpdatedDate = DateTime.UtcNow,
                ActorId = sessionService.User.Id

            };
            return await baseService.ExecuteNonQueryAsync(criteria);
        }
    }
}
