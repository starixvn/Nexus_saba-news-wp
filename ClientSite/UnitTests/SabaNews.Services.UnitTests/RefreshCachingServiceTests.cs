namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using Fanex.Caching;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.Banner;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class RefreshCachingServiceTests
    {
        private IFetchDataService fetchDataService;
        private ICacheWrapperService cacheWrapperService;
        private IPostService postService;
        private ILogServiceProvider logService;
        private IBannerService bannerService;
        private IFixture fixture = new Fixture();

        [Fact]
        public async Task UpdateHomePageCaching_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var data = fixture.Create<PostViewModel>();

            fetchDataService.GetHomePageViewModel().Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));
            

            //Act
            await service.UpdateHomePageCaching();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateHomePageCaching_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var exception = fixture.Create<Exception>();

            fetchDataService.When(x => x.GetHomePageViewModel()).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateHomePageCaching();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }

        [Fact]
        public async Task UpdateMenuCaching_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var data = fixture.Create<IEnumerable<Menu>>();

            fetchDataService.GetMenuData().Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateMenuCaching();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateMenuCaching_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var exception = fixture.Create<Exception>();

            fetchDataService.When(x => x.GetMenuData()).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateMenuCaching();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }

        [Fact]
        public async Task UpdateCategoryCaching_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var slug = fixture.Create<string>();
            var page = fixture.Create<int>();
            var data = fixture.Create<BlogViewModel>();
            var param = fixture.Create<GetPostsByCateSlugsParam>();

            fetchDataService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x)).Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateCategoryCaching();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateCategoryCaching_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var slug = fixture.Create<string>();
            var page = fixture.Create<int>();
            var exception = fixture.Create<Exception>();
            var param = fixture.Create<GetPostsByCateSlugsParam>();

            fetchDataService.When(x => x.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x))).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateCategoryCaching();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }

        [Fact]
        public async Task UpdateSitemapData_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var data = fixture.Create<IEnumerable<SitemapModel>>();

            postService.GetSitemapDataAsync().Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateSitemapData();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateSitemapData_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var exception = fixture.Create<Exception>();

            postService.When(x => x.GetSitemapDataAsync()).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateSitemapData();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }

        [Fact]
        public async Task UpdateNewsSitemapData_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var data = fixture.Create<IEnumerable<NewsSitemapModel>>();

            postService.GetNewsSitemapDataAsync().Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateNewsSitemapData();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateNewsSitemapData_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var exception = fixture.Create<Exception>();

            postService.When(x => x.GetNewsSitemapDataAsync()).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateNewsSitemapData();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }

        [Fact]
        public async Task UpdateRSSData_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var data = fixture.Create<IEnumerable<NewsSitemapModel>>();

            postService.GetNewsSitemapDataAsync().Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateRSSData();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateRSSData_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var exception = fixture.Create<Exception>();

            postService.When(x => x.GetNewsSitemapDataAsync()).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateRSSData();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }

        [Fact]
        public async Task UpdateBannerCaching_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var data = fixture.Create<BannerModel>();

            bannerService.GetBannerInfoAsync().Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateBannerCaching();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateBannerCaching_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var exception = fixture.Create<Exception>();

            bannerService.When(x => x.GetBannerInfoAsync()).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateBannerCaching();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }

        [Fact]
        public async Task UpdateWorldCupCaching_WhenCalled_CallExpectedFunction()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var data = fixture.Create<WorldCupViewModel>();

            fetchDataService.GetWorldCupViewModel().Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateWorldCupCaching();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task UpdateWorldCupCaching_WhenCalled_ThrowException()
        {
            //Arrange
            var service = GetRefreshCachingService();
            var logMessage = fixture.Create<string>();
            var exception = fixture.Create<Exception>();

            fetchDataService.When(x => x.GetWorldCupViewModel()).Throw(exception);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateWorldCupCaching();

            //Assert
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
        }


        #region Helpers
        private RefreshCachingService GetRefreshCachingService()
        {
            fetchDataService = Substitute.For<IFetchDataService>();
            cacheWrapperService = Substitute.For<ICacheWrapperService>();
            postService = Substitute.For<IPostService>();
            logService = Substitute.For<ILogServiceProvider>();
            bannerService = Substitute.For<IBannerService>();
            var service = new RefreshCachingService(fetchDataService, cacheWrapperService, postService, logService, bannerService);

            return service;
        }
        #endregion Helpers
    }
}
