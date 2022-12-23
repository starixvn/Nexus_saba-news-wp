namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models.Posts;
    using SabaNews.Services.Interfaces;

    public class RefreshCachingService : IRefreshCachingService
    {
        private readonly IFetchDataService fetchDataService;
        private readonly ICacheWrapperService cacheWrapperService;
        private readonly IPostService postService;
        private readonly IBannerService bannerService;
        private readonly ILogServiceProvider logService;
        private readonly IMatchService matchService;

        public RefreshCachingService(IFetchDataService fetchDataService,
            ICacheWrapperService cacheWrapperService,
            IPostService postService,
            ILogServiceProvider logService,
            IBannerService bannerService,
            IMatchService matchService)
        {
            this.fetchDataService = fetchDataService;
            this.cacheWrapperService = cacheWrapperService;
            this.postService = postService;
            this.logService = logService;
            this.bannerService = bannerService;
            this.matchService = matchService;
        }

        public async Task UpdateHomePageCaching()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateHomePageCaching");
            try
            {
                var data = await fetchDataService.GetHomePageViewModel();
                logService.WriteLogFileInfo(string.Format("After GetHomePageViewModel - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.HomePageViewModel,
                        data,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheHomePageTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateHomePageCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateMenuCaching()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateMenuCaching");

            try
            {
                var data = await fetchDataService.GetMenuData();
                logService.WriteLogFileInfo(string.Format("After GetMenuData - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.Menu,
                        data,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheMenuTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }
            logService.WriteLogFileInfo(string.Format("Done UpdateMenuCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateCategoryCaching()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateCategoryCaching");

            try
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
                    var param = new GetPostsByCateSlugsParam { Slug1 = slug, Page = Constants.FirstPage };
                    var data = await fetchDataService.GetCategoryViewModel(param);
                    logService.WriteLogFileInfo(string.Format("After GetCategoryViewModel - {1} - {0}", DateTime.Now.Subtract(startTime).Milliseconds, slug));
                    await cacheWrapperService.SetAsync(key,
                        data,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheCategoryTimeoutMinutes)
                        });
                }
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateCategoryCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateSitemapData()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateSitemapData");

            try
            {
                var result = await postService.GetSitemapDataAsync();
                logService.WriteLogFileInfo(string.Format("After GetSitemapDataAsync - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.SitemapXml,
                        result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheSitemapTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateSitemapData - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateNewsSitemapData()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateNewsSitemapData");

            try
            {
                var result = await postService.GetNewsSitemapDataAsync();
                logService.WriteLogFileInfo(string.Format("After GetNewsSitemapDataAsync - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.NewsSitemapXml,
                        result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheNewSitemapTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateNewsSitemapData - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateRSSData()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateRSSData");

            try
            {
                var result = await postService.GetNewsSitemapDataAsync();
                logService.WriteLogFileInfo(string.Format("After GetNewsSitemapDataAsync - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.RSS,
                        result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheRSSTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateRSSData - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateBannerCaching()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateBannerCaching");

            try
            {
                var result = await bannerService.GetBannerInfoAsync();
                logService.WriteLogFileInfo(string.Format("After UpdateBannerCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.Banner,
                        result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheBannerTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateBannerCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateWorldCupCaching()
        {
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start RefreshWorldCupCaching");

            try
            {
                var result = await fetchDataService.GetWorldCupViewModel();
                logService.WriteLogFileInfo(string.Format("After RefreshWorldCupCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.WorldCup,
                        result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheWorldCupTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done RefreshWorldCupCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }

        public async Task UpdateHomepageFixtureCaching()
        {
            var criteria = new GetMatchBySeasonCriteria { LeagueId = HomeFixture.LeagueId, SeasonId = HomeFixture.SeasonId };
            var startTime = DateTime.Now;
            logService.WriteLogFileInfo("Start UpdateHomepageFixtureCaching");

            try
            {
                (League league, IList<Match> matches) = await matchService.GetMatchBySeasonAsync(criteria);
                logService.WriteLogFileInfo(string.Format("After UpdateHomepageFixtureCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
                await cacheWrapperService.SetAsync(CacheKey.FixtureHomePage,
                        (league, matches),
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CacheHompageFixtureTimeoutMinutes)
                        });
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo(ex.ToString());
            }

            logService.WriteLogFileInfo(string.Format("Done UpdateHomepageFixtureCaching - {0}", DateTime.Now.Subtract(startTime).Milliseconds));
        }
    }
}