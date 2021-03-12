namespace SabaNews.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AspNetCore.SEOHelper.Sitemap;
    using Fanex.Caching;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;

    public class DataControlService : IDataControlService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly ICacheService cacheService;
        private readonly IPostService postService;
        private readonly bool IsUsingCache;

        public DataControlService(IFetchDataService fetchDataService, IConfiguration configuration, ICacheService cacheService, IPostService postService)
        {
            this.fetchDataService = fetchDataService;
            this.cacheService = cacheService;
            this.postService = postService;
            IsUsingCache = configuration.GetValue<bool>("IsEnabledUseFanexCaching");
        }

        public async Task<PostViewModel> GetHomePageViewModel()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<PostViewModel>(CacheKey.HomePageViewModel);

                if (result == null)
                {
                    result = await fetchDataService.GetHomePageViewModel();
                    await cacheService.SetAsync(CacheKey.HomePageViewModel, result);
                }

                return result;
            }

            return await fetchDataService.GetHomePageViewModel();
        }

        public async Task<CategoryViewModel> GetCategoryViewModel(string slug, int page)
        {
            if (IsUsingCache && page == Constants.FirstPage)
            {
                var cacheKey = CacheKey.CategoryViewModel + "-" + slug;
                var result = await cacheService.GetAsync<CategoryViewModel>(cacheKey);

                if (result == null)
                {
                    result = await fetchDataService.GetCategoryViewModel(slug, page);
                    if (result != null)
                    {
                        await cacheService.SetAsync(cacheKey, result);
                    }
                }

                return result;
            }

            return await fetchDataService.GetCategoryViewModel(slug, page);
        }

        public async Task<IEnumerable<Menu>> GetMenuData()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<IEnumerable<Menu>>(CacheKey.Menu);

                if (result == null)
                {
                    result = await fetchDataService.GetMenuData();
                    await cacheService.SetAsync(CacheKey.Menu, result);
                }

                return result;
            }

            return await fetchDataService.GetMenuData();
        }

        public async Task<IEnumerable<SitemapNode>> GenerateSitmapXmlData()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<IEnumerable<SitemapNode>>(CacheKey.SitemapXml);

                if (result == null)
                {
                    result = await postService.GetSitemapDataAsync();
                    await cacheService.SetAsync(CacheKey.SitemapXml, result);
                }

                return result;
            }

            return await postService.GetSitemapDataAsync();
        }
    }
}