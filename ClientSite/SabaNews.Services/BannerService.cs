namespace SabaNews.Services
{
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Banner;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class BannerService : IBannerService
    {
        private readonly IBaseService baseService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly string baseUrl;
        private const string DefaultDestopTopUrl = "images/Desktop_320x70.png";
        private const string DefaultMobileTopUrl = "images/Mobile_375x200.png";
        private const string DefaultDesktopCenterUrl = "images/banner.jpg";
        private const string DefaultDesktopLeftUrl = "images/ads.png";
        private const string DefaultDesktopRightUrl = "images/ads.png";
        private const string DefaultMobileCenterUrl = "images/banner_mobile_domain.png";

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
            banner.DesktopTopUrl = string.IsNullOrEmpty(banner.DesktopTopUrl) ? string.Concat(baseUrl, DefaultDestopTopUrl) : banner.DesktopTopUrl;
            banner.MobileTopUrl = string.IsNullOrEmpty(banner.MobileTopUrl) ? string.Concat(baseUrl, DefaultMobileTopUrl) : banner.MobileTopUrl;
            banner.DesktopCenterUrl = string.IsNullOrEmpty(banner.DesktopCenterUrl) ? string.Concat(baseUrl, DefaultDesktopCenterUrl) : banner.DesktopCenterUrl;
            banner.DesktopLeftUrl = string.IsNullOrEmpty(banner.DesktopLeftUrl) ? string.Concat(baseUrl, DefaultDesktopLeftUrl) : banner.DesktopLeftUrl;
            banner.DesktopRightUrl = string.IsNullOrEmpty(banner.DesktopRightUrl) ? string.Concat(baseUrl, DefaultDesktopRightUrl) : banner.DesktopRightUrl;
            banner.MobileCenterUrl = string.IsNullOrEmpty(banner.MobileCenterUrl) ? string.Concat(baseUrl, DefaultMobileCenterUrl) : banner.MobileCenterUrl;
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
                MobileBottomUrl = banner.MobileBottomUrl,
                MobileBottomNavigationUrl = banner.MobileBottomNavigationUrl,
                ActorId = sessionService.User.Id,
            };
            return await baseService.ExecuteNonQueryAsync(criteria);
        }
    }
}
