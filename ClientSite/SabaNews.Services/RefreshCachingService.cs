namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;

    public class RefreshCachingService : IRefreshCachingService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly ICacheService cacheService;
        private readonly IPostService postService;
        private readonly ILogServiceProvider logService;

        public RefreshCachingService(IFetchDataService fetchDataService,
            ICacheService cacheService,
            IPostService postService,
            ILogServiceProvider logService)
        {
            this.fetchDataService = fetchDataService;
            this.cacheService = cacheService;
            this.postService = postService;
            this.logService = logService;
        }

        public async Task UpdateHomePageCaching()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateHomePageCaching");

            try
            {
                var data = await fetchDataService.GetHomePageViewModel();
                logService.WriteLogFileInfo(string.Format("After GetHomePageViewModel - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheService.SetAsync(CacheKey.HomePageViewModel, data);
            }
            catch(Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateHomePageCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateMenuCaching()
        {
            var data = await fetchDataService.GetMenuData();

            await cacheService.SetAsync(CacheKey.Menu, data);
        }

        public async Task UpdateCategoryCaching()
        {
            var listCategoryCaching = new List<string>
            {
                Constants.FootballSlug,
                Constants.GuessSlug,
                Constants.BackstageSlug,
                Constants.TransferSlug,
                Constants.BasketballSlug,
                Constants.TennisSlug,
                Constants.VideoSlug
            };
            foreach (var slug in listCategoryCaching)
            {
                var key = CacheKey.CategoryViewModel + "-" + slug;
                var data = await fetchDataService.GetCategoryViewModel(slug, Constants.FirstPage);
                await cacheService.SetAsync(key, data);
            }
        }

        public async Task UpdateSitemapData()
        {
            var result = await postService.GetSitemapDataAsync();
            await cacheService.SetAsync(CacheKey.SitemapXml, result);
        }

        public async Task UpdateNewsSitemapData()
        {
            var result = await postService.GetNewsSitemapDataAsync();
            await cacheService.SetAsync(CacheKey.NewsSitemapXml, result);
        }

        public async Task UpdateRSSData()
        {
            var result = await postService.GetNewsSitemapDataAsync();
            await cacheService.SetAsync(CacheKey.RSS, result);
        }

        public async Task UpdateEuro2020PageCaching()
        {
            var data = await fetchDataService.GetEuro2020ViewModel();

            await cacheService.SetAsync(CacheKey.Euro2020PageViewModel, data);
        }
    }
}