namespace SabaNews.Services
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using AspNetCore.SEOHelper.Sitemap;
    using Fanex.Caching;
    using Microsoft.AspNetCore.Hosting;
    using SabaNews.Core.Constants;
    using SabaNews.Services.Interfaces;

    public class RefreshCachingService : IRefreshCachingService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly ICacheService cacheService;
        private readonly IPostService postService;
        private readonly IWebHostEnvironment webHostEnvironment;

        public RefreshCachingService(IFetchDataService fetchDataService,
            ICacheService cacheService,
            IPostService postService,
            IWebHostEnvironment webHostEnvironment)
        {
            this.fetchDataService = fetchDataService;
            this.cacheService = cacheService;
            this.postService = postService;
            this.webHostEnvironment = webHostEnvironment;
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

        public async Task UpdateSitemapXml()
        {
            var sitemap = await postService.GetSitemapDataAsync();
            var siteMapNodes = sitemap.Select(item => new SitemapNode
            {
                Url = item.Url,
                LastModified = item.ModifiedDate,
            });
            var siteMapDocument = new SitemapDocument();
            siteMapDocument.CreateSitemapXML(siteMapNodes, webHostEnvironment.ContentRootPath);
        }
    }
}