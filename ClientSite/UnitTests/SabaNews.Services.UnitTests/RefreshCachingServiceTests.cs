namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using Fanex.Caching;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class RefreshCachingServiceTests
    {
        private IFetchDataService fetchDataService;
        private ICacheService cacheService;
        private IPostService postService;
        private ILogServiceProvider logService;
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
            await cacheService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
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
            await cacheService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
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
            var data = fixture.Create<CategoryViewModel>();

            fetchDataService.GetCategoryViewModel(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => page = x)).Returns(data);
            logService.WriteLogFileInfo(Arg.Do<string>(x => logMessage = x));


            //Act
            await service.UpdateCategoryCaching();

            //Assert
            logService.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
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

            fetchDataService.When(x => x.GetCategoryViewModel(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => page = x))).Throw(exception);
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
            await cacheService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
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
            await cacheService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
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
            await cacheService.Received().SetAsync(Arg.Any<string>(), data, Arg.Any<CacheItemOptions>());
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


        #region Helpers
        private RefreshCachingService GetRefreshCachingService()
        {
            fetchDataService = Substitute.For<IFetchDataService>();
            cacheService = Substitute.For<ICacheService>();
            postService = Substitute.For<IPostService>();
            logService = Substitute.For<ILogServiceProvider>();
            var service = new RefreshCachingService(fetchDataService, cacheService, postService, logService);

            return service;
        }
        #endregion Helpers
    }
}
