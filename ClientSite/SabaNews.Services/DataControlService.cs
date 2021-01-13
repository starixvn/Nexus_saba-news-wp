namespace SabaNews.Services
{
    using Fanex.Caching;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class DataControlService : IDataControlService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly bool IsUsingCache;
        private readonly ICacheService cacheService;

        public DataControlService(IFetchDataService fetchDataService, IConfiguration configuration, ICacheService cacheService)
        {
            this.fetchDataService = fetchDataService;
            IsUsingCache = configuration.GetValue<bool>("IsEnabledUseFanexCaching");
            this.cacheService = cacheService;
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
    }
}