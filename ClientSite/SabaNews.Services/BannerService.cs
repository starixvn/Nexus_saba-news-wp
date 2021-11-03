namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Banner;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class BannerService : IBannerService
    {
        private readonly IBaseService baseService;
        public BannerService(IBaseService baseService)
        {
            this.baseService = baseService;
        }
        public async Task<Banner> GetBannerInfoAsync()
        {
            return await baseService.GetDataEntityAsync<Banner>(new GetBannerInfoCriteria());
        }

        public async Task<CommandResult> UpdateBannerInfoAsync(Banner banner)
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
