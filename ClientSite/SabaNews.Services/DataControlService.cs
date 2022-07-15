namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.Banner;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;

    public class DataControlService : IDataControlService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly ICacheWrapperService cacheWrapperService;
        private readonly IPostService postService;
        private readonly ILogServiceProvider logServiceProvider;
        private readonly IBannerService bannerService;
        private readonly bool IsUsingCache;

        public DataControlService(IFetchDataService fetchDataService,
            IConfiguration configuration,
            ICacheWrapperService cacheWrapperService,
            IPostService postService,
            ILogServiceProvider logServiceProvider,
            IBannerService bannerService)
        {
            this.fetchDataService = fetchDataService;
            this.cacheWrapperService = cacheWrapperService;
            this.postService = postService;
            this.logServiceProvider = logServiceProvider;
            this.bannerService = bannerService;
            IsUsingCache = configuration.GetValue<bool>("IsEnabledUseFanexCaching");
        }

        public async Task<PostViewModel> GetHomePageViewModel()
        {
            if (IsUsingCache)
            {
                var result = await cacheWrapperService.GetAsync<PostViewModel>(CacheKey.HomePageViewModel);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo("-------Get Homepage data from DB---------");
                    result = await fetchDataService.GetHomePageViewModel();
                    await cacheWrapperService.SetAsync(CacheKey.HomePageViewModel, result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheHomePageTimeoutMinutes)
                        });
                }

                return result;
            }

            return await fetchDataService.GetHomePageViewModel();
        }

        public async Task<BlogViewModel> GetCategoryViewModel(GetPostsByCateSlugsParam param)
        {
            if (IsUsingCache && param.Page == Constants.FirstPage && param.Slug2 == null)
            {
                var slug = param.Slug1;
                var cacheKey = CacheKey.CategoryViewModel + "-" + slug;
                var result = await cacheWrapperService.GetAsync<BlogViewModel>(cacheKey);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get Category {slug} data from DB---------");

                    result = await fetchDataService.GetCategoryViewModel(param);
                    if (result != null)
                    {
                        await cacheWrapperService.SetAsync(cacheKey, result,
                            new CacheItemOptions
                            {
                                RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheCategoryTimeoutMinutes)
                            });
                    }
                }

                return result;
            }

            return await fetchDataService.GetCategoryViewModel(param);
        }

        public async Task<IEnumerable<Menu>> GetMenuData()
        {
            if (IsUsingCache)
            {
                var result = await cacheWrapperService.GetAsync<IEnumerable<Menu>>(CacheKey.Menu);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get Menu data from DB---------");

                    result = await fetchDataService.GetMenuData();
                    await cacheWrapperService.SetAsync(CacheKey.Menu, result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheMenuTimeoutMinutes)
                        });
                }

                return result;
            }

            return await fetchDataService.GetMenuData();
        }

        public async Task<IEnumerable<SitemapModel>> GenerateSitmapXmlData()
        {
            if (IsUsingCache)
            {
                var result = await cacheWrapperService.GetAsync<IEnumerable<SitemapModel>>(CacheKey.SitemapXml);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get Sitemap data from DB---------");

                    result = await postService.GetSitemapDataAsync();
                    await cacheWrapperService.SetAsync(CacheKey.SitemapXml, result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheSitemapTimeoutMinutes)
                        });
                }

                return result;
            }

            return await postService.GetSitemapDataAsync();
        }

        public async Task<IEnumerable<NewsSitemapModel>> GenerateNewsSitmapXmlData()
        {
            if (IsUsingCache)
            {
                var result = await cacheWrapperService.GetAsync<IEnumerable<NewsSitemapModel>>(CacheKey.NewsSitemapXml);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get News Sitemap data from DB---------");

                    result = await postService.GetNewsSitemapDataAsync();
                    await cacheWrapperService.SetAsync(CacheKey.NewsSitemapXml, result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheNewSitemapTimeoutMinutes)
                        });
                }

                return result;
            }

            return await postService.GetNewsSitemapDataAsync();
        }

        public async Task<IEnumerable<Post>> GenerateRSSData()
        {
            if (IsUsingCache)
            {
                var result = await cacheWrapperService.GetAsync<IEnumerable<Post>>(CacheKey.RSS);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get RSS data from DB---------");

                    result = await postService.GetRSSDataAsync();
                    await cacheWrapperService.SetAsync(CacheKey.RSS, result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheRSSTimeoutMinutes)
                        });
                }

                return result;
            }

            return await postService.GetRSSDataAsync();
        }

        public async Task<BannerModel> GetBannerData()
        {
            if (IsUsingCache)
            {
                var result = await cacheWrapperService.GetAsync<BannerModel>(CacheKey.Banner);

                if (result == null)
                {
                    logServiceProvider.WriteLogFileInfo($"-------Get Banner data from DB---------");

                    result = await bannerService.GetBannerInfoAsync();
                    await cacheWrapperService.SetAsync(CacheKey.Banner, result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheBannerTimeoutMinutes)
                        });
                }

                return result;
            }

            return await bannerService.GetBannerInfoAsync();
        }

        public async Task<Euro2020ViewModel> GetEuro2020PageViewModel()
        {
            var result = await fetchDataService.GetEuro2020ViewModel();
            return result;
        }
    }
}