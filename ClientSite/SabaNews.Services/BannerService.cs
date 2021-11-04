namespace SabaNews.Services
{
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Banner;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class BannerService : IBannerService
    {
        private readonly IBaseService baseService;
        private readonly IConfiguration configuration;
        private readonly string baseUrl;
        private const string DefaultDestopUrl = "images/Desktop_320x70.png";
        private const string DefaultMobileUrl = "images/Mobile_375x200.png";

        public BannerService(IBaseService baseService, IConfiguration configuration)
        {
            this.baseService = baseService;
            this.configuration = configuration;
            baseUrl = configuration.GetValue<string>("BaseUrl");
        }
        public async Task<BannerModel> GetBannerInfoAsync()
        {
            var banner =  await baseService.GetDataEntityAsync<BannerModel>(new GetBannerInfoCriteria());
            banner.DesktopUrl = string.IsNullOrEmpty(banner.DesktopUrl) ? string.Concat(baseUrl, DefaultDestopUrl) : banner.DesktopUrl;
            banner.MobileUrl = string.IsNullOrEmpty(banner.MobileUrl) ? string.Concat(baseUrl, DefaultMobileUrl) : banner.MobileUrl;
            return banner;

        }

        public async Task<CommandResult> UpdateBannerInfoAsync(BannerModel banner)
        {
            var criteria = new UpdateBannerInfoCommand
            {
                NaviagationUrl = banner.NavigationUrl,
                DesktopUrl = banner.DesktopUrl,
                MobileUrl = banner.MobileUrl
            };
            return await baseService.ExecuteNonQueryAsync(criteria);
        }
    }
}
