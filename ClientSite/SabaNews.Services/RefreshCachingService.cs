namespace SabaNews.Services
{
    using Fanex.Caching;
    using SabaNews.Core.Constants;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class RefreshCachingService : IRefreshCachingService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly ICacheService cacheService;

        public RefreshCachingService(IFetchDataService fetchDataService, ICacheService cacheService)
        {
            this.fetchDataService = fetchDataService;
            this.cacheService = cacheService;
        }

        public async Task UpdateHomePageCaching()
        {
            var data = await fetchDataService.GetHomePageViewModel();

            await cacheService.SetAsync(CacheKey.HomePageViewModel, data);
        }

        public async Task UpdateMenuCaching()
        {
            var data = await fetchDataService.GetMenuData();

            await cacheService.SetAsync(CacheKey.Menu, data);
        }
    }
}