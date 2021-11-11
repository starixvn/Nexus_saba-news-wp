namespace SabaNews.Services
{
    using Fanex.Caching;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class DataControlService : IDataControlService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly ICacheService cacheService;
        private readonly IPostService postService;
        private readonly ILogServiceProvider logServiceProvider;
        private readonly bool IsUsingCache;

        public DataControlService(IFetchDataService fetchDataService, 
            IConfiguration configuration, 
            ICacheService cacheService,
            IPostService postService,
            ILogServiceProvider logServiceProvider)
        {
            this.fetchDataService = fetchDataService;
            this.cacheService = cacheService;
            this.postService = postService;
            this.logServiceProvider = logServiceProvider;
            IsUsingCache = configuration.GetValue<bool>("IsEnabledUseFanexCaching");
        }

        public async Task<PostViewModel> GetHomePageViewModel()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<PostViewModel>(CacheKey.HomePageViewModel);
                
                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo("-------Get Homepage data from DB---------");
                    result = await fetchDataService.GetHomePageViewModel();
                    //await cacheService.SetAsync(CacheKey.HomePageViewModel, result,
                    //    new CacheItemOptions
                    //    {
                    //        RelativeExpiration = TimeSpan.FromMinutes(20)
                    //    });
                }
                else
                {
                    logServiceProvider.WriteLogFileInfo("-------Get Homepage data from Caching---------");
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
                    logServiceProvider.WriteLogFileInfo($"-------Get Category {slug} data from DB---------");

                    result = await fetchDataService.GetCategoryViewModel(slug, page);
                    //if (result != null)
                    //{
                    //    await cacheService.SetAsync(cacheKey, result,
                    //        new CacheItemOptions
                    //        {
                    //            RelativeExpiration = TimeSpan.FromMinutes(10)
                    //        });
                    //}
                }
                else
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get Category {slug} data from Caching---------");
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
                    logServiceProvider.WriteLogFileInfo($"-------Get Menu data from DB---------");

                    result = await fetchDataService.GetMenuData();
                    //await cacheService.SetAsync(CacheKey.Menu, result,
                    //    new CacheItemOptions
                    //    {
                    //        RelativeExpiration = TimeSpan.FromMinutes(700)
                    //    });
                }
                else
                {
                    logServiceProvider.WriteLogFileInfo("-------Get Menu data from Caching---------");
                }

                return result;
            }

            return await fetchDataService.GetMenuData();
        }

        public async Task<IEnumerable<SitemapModel>> GenerateSitmapXmlData()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<IEnumerable<SitemapModel>>(CacheKey.SitemapXml);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get Sitemap data from DB---------");

                    result = await postService.GetSitemapDataAsync();
                    //await cacheService.SetAsync(CacheKey.SitemapXml, result,
                    //    new CacheItemOptions
                    //    {
                    //        RelativeExpiration = TimeSpan.FromMinutes(30)
                    //    });
                }
                else
                {
                    logServiceProvider.WriteLogFileInfo("-------Get Sitemap data from Caching---------");
                }

                return result;
            }

            return await postService.GetSitemapDataAsync();
        }

        public async Task<IEnumerable<NewsSitemapModel>> GenerateNewsSitmapXmlData()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<IEnumerable<NewsSitemapModel>>(CacheKey.NewsSitemapXml);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get News Sitemap data from DB---------");

                    result = await postService.GetNewsSitemapDataAsync();
                    //await cacheService.SetAsync(CacheKey.NewsSitemapXml, result,
                    //    new CacheItemOptions
                    //    {
                    //        RelativeExpiration = TimeSpan.FromMinutes(30)
                    //    });
                }
                else
                {
                    logServiceProvider.WriteLogFileInfo("-------Get News Sitemap data from Caching---------");
                }

                return result;
            }

            return await postService.GetNewsSitemapDataAsync();
        }

        public async Task<IEnumerable<Post>> GenerateRSSData()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<IEnumerable<Post>>(CacheKey.RSS);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get RSS data from DB---------");

                    result = await postService.GetRSSDataAsync();
                    //await cacheService.SetAsync(CacheKey.RSS, result,
                    //    new CacheItemOptions
                    //    {
                    //        RelativeExpiration = TimeSpan.FromMinutes(30)
                    //    });
                }
                else
                {
                    logServiceProvider.WriteLogFileInfo("-------Get RSS data from Caching---------");
                }

                return result;
            }

            return await postService.GetRSSDataAsync();
        }

        public async Task<Euro2020ViewModel> GetEuro2020PageViewModel()
        {
            if (IsUsingCache)
            {
                var result = await cacheService.GetAsync<Euro2020ViewModel>(CacheKey.Euro2020PageViewModel);

                if (result == null)
                {
                    result = await fetchDataService.GetEuro2020ViewModel();
                    await cacheService.SetAsync(CacheKey.Euro2020PageViewModel, result);
                }
                return result;
            }
            return await fetchDataService.GetEuro2020ViewModel();
        }
    }
}