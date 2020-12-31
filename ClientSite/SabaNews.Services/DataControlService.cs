namespace SabaNews.Services
{
    using Fanex.Caching;
    using Microsoft.Extensions.Configuration;
    using sabanews.Models;
    using SabaNews.Core.Constants;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class DataControlService : IDataControlService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly IConfiguration configuration;
        private readonly ICacheService cacheService;

        public DataControlService(IFetchDataService fetchDataService, IConfiguration configuration, ICacheService cacheService)
        {
            this.fetchDataService = fetchDataService;
            this.configuration = configuration;
            this.cacheService = cacheService;
        }

        public async Task<PostViewModel> GetHomePageViewModel()
        {
            bool isEnabledCache = configuration.GetValue<bool>("IsEnabledUseFanexCaching");
            if (isEnabledCache)
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
    }
}