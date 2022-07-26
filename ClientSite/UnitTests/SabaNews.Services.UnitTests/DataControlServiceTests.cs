using AutoFixture;
using Fanex.Caching;
using Microsoft.Extensions.Configuration;
using NSubstitute;
using SabaNews.Core.Constants;
using SabaNews.Core.Services;
using SabaNews.Models;
using SabaNews.Models.Banner;
using SabaNews.Models.Posts;
using SabaNews.Models.ViewModel;
using SabaNews.Models.ViewModel.Posts;
using SabaNews.Services.Interfaces;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Services.UnitTests
{
    public class DataControlServiceTests
    {

        private IFetchDataService fetchDataService;
        private ICacheWrapperService cacheWrapperService;
        private IPostService postService;
        private ILogServiceProvider logServiceProvider;
        private IConfiguration configuration;
        private IBannerService bannerService;
        private readonly IFixture fixture = new Fixture();


        [Fact]
        public async Task GetHomePageViewModel_WhenCacheHaveData_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<PostViewModel>();
            cacheWrapperService.GetAsync<PostViewModel>(Arg.Do<string>(x => x = key)).Returns(expected);
            //Act
            var actual = await service.GetHomePageViewModel();

            //Assert
            Assert.Equal(expected, actual);
            
        }

        [Fact]
        public async Task GetHomePageViewModel_WhenCacheReturnNull_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            PostViewModel expectedFromCache = null;
            var expected = fixture.Create<PostViewModel>();
            cacheWrapperService.GetAsync<PostViewModel>(Arg.Do<string>(x => x = key)).Returns(expectedFromCache);
            fetchDataService.GetHomePageViewModel().Returns(expected);

            //Act
            var actual = await service.GetHomePageViewModel();

            //Assert
            Assert.Equal(expected, actual);
            logServiceProvider.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), Arg.Any<PostViewModel>(), Arg.Any<CacheItemOptions>());

        }

        [Fact]
        public async Task GetHomePageViewModel_WhenNotUsingCache_ReturnExpectedData()
        {

            var service = GetDataControlServiceNotUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<PostViewModel>();
            fetchDataService.GetHomePageViewModel().Returns(expected);

            //Act
            var actual = await service.GetHomePageViewModel();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GetCategoryViewModel_WhenCacheHaveData_ReturnExpectedData()
        {
            var service = GetDataControlServiceUsingCache();
            var slug = fixture.Create<string>();
            var key = fixture.Create<string>();
            var param = new GetPostsByCateSlugsParam { Slug1 = Constants.FootballSlug, Page = 1 };
            var expected = fixture.Create<BlogViewModel>();
            cacheWrapperService.GetAsync<BlogViewModel>(Arg.Do<string>(x => x = key)).Returns(expected);
            //Act
            var actual = await service.GetCategoryViewModel(param);

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GetCategoryViewModel_WhenCacheReturnNull_ReturnExpectedData()
        {
            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            var slug = fixture.Create<string>();
            var param = new GetPostsByCateSlugsParam { Slug1 = Constants.FootballSlug, Page = 1 };
            BlogViewModel expectedFromCache = null;
            var expected = fixture.Create<BlogViewModel>();
            cacheWrapperService.GetAsync<BlogViewModel>(Arg.Do<string>(x => x = key)).Returns(expectedFromCache);
            fetchDataService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => x = param)).Returns(expected);

            //Act
            var actual = await service.GetCategoryViewModel(param);

            //Assert
            Assert.Equal(expected, actual);
            logServiceProvider.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), Arg.Any<BlogViewModel>(), Arg.Any<CacheItemOptions>());

        }

        [Fact]
        public async Task GetCategoryViewModel_WhenPageIsNotFirstPage_ReturnExpectedData()
        {
            var service = GetDataControlServiceNotUsingCache();
            var key = fixture.Create<string>();
            var slug = fixture.Create<string>();
            var page = 2;
            var param = new GetPostsByCateSlugsParam { Page = 2, Slug1 = "slug1", Slug2 = "slug2" };
            var expected = fixture.Create<BlogViewModel>();
            fetchDataService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => x = param)).Returns(expected);

            //Act
            var actual = await service.GetCategoryViewModel(param);

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GetMenuData_WhenCacheHaveData_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<Menu>>();
            cacheWrapperService.GetAsync<IEnumerable<Menu>>(Arg.Do<string>(x => x = key)).Returns(expected);
            //Act
            var actual = await service.GetMenuData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GetMenuData_WhenCacheReturnNull_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            IEnumerable<Menu> expectedFromCache = null;
            var expected = fixture.Create<IEnumerable<Menu>>();
            cacheWrapperService.GetAsync<IEnumerable<Menu>>(Arg.Do<string>(x => x = key)).Returns(expectedFromCache);
            fetchDataService.GetMenuData().Returns(expected);

            //Act
            var actual = await service.GetMenuData();

            //Assert
            Assert.Equal(expected, actual);
            logServiceProvider.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IEnumerable<Menu>>(), Arg.Any<CacheItemOptions>());

        }

        [Fact]
        public async Task GetMenuData_WhenNotUsingCache_ReturnExpectedData()
        {

            var service = GetDataControlServiceNotUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<Menu>>();
            fetchDataService.GetMenuData().Returns(expected);

            //Act
            var actual = await service.GetMenuData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GenerateSitmapXmlData_WhenCacheHaveData_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<SitemapModel>>();
            cacheWrapperService.GetAsync<IEnumerable<SitemapModel>>(Arg.Do<string>(x => x = key)).Returns(expected);
            //Act
            var actual = await service.GenerateSitmapXmlData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GenerateSitmapXmlData_WhenCacheReturnNull_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            IEnumerable<SitemapModel> expectedFromCache = null;
            var expected = fixture.Create<IEnumerable<SitemapModel>>();
            cacheWrapperService.GetAsync<IEnumerable<SitemapModel>>(Arg.Do<string>(x => x = key)).Returns(expectedFromCache);
            postService.GetSitemapDataAsync().Returns(expected);

            //Act
            var actual = await service.GenerateSitmapXmlData();

            //Assert
            Assert.Equal(expected, actual);
            logServiceProvider.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IEnumerable<SitemapModel>>(), Arg.Any<CacheItemOptions>());

        }

        [Fact]
        public async Task GenerateSitmapXmlData_WhenNotUsingCache_ReturnExpectedData()
        {

            var service = GetDataControlServiceNotUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<SitemapModel>>();
            postService.GetSitemapDataAsync().Returns(expected);

            //Act
            var actual = await service.GenerateSitmapXmlData();

            //Assert
            Assert.Equal(expected, actual);

        }


        [Fact]
        public async Task GenerateNewsSitmapXmlData_WhenCacheHaveData_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<NewsSitemapModel>>();
            cacheWrapperService.GetAsync<IEnumerable<NewsSitemapModel>>(Arg.Do<string>(x => x = key)).Returns(expected);
            //Act
            var actual = await service.GenerateNewsSitmapXmlData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GenerateNewsSitmapXmlData_WhenCacheReturnNull_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            IEnumerable<NewsSitemapModel> expectedFromCache = null;
            var expected = fixture.Create<IEnumerable<NewsSitemapModel>>();
            cacheWrapperService.GetAsync<IEnumerable<NewsSitemapModel>>(Arg.Do<string>(x => x = key)).Returns(expectedFromCache);
            postService.GetNewsSitemapDataAsync().Returns(expected);

            //Act
            var actual = await service.GenerateNewsSitmapXmlData();

            //Assert
            Assert.Equal(expected, actual);
            logServiceProvider.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IEnumerable<NewsSitemapModel>>(), Arg.Any<CacheItemOptions>());

        }

        [Fact]
        public async Task GenerateNewsSitmapXmlData_WhenNotUsingCache_ReturnExpectedData()
        {

            var service = GetDataControlServiceNotUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<NewsSitemapModel>>();
            postService.GetNewsSitemapDataAsync().Returns(expected);

            //Act
            var actual = await service.GenerateNewsSitmapXmlData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GenerateRSSData_WhenCacheHaveData_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<Post>>();
            cacheWrapperService.GetAsync<IEnumerable<Post>>(Arg.Do<string>(x => x = key)).Returns(expected);
            //Act
            var actual = await service.GenerateRSSData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GenerateRSSData_WhenCacheReturnNull_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            IEnumerable<Post> expectedFromCache = null;
            var expected = fixture.Create<IEnumerable<Post>>();
            cacheWrapperService.GetAsync<IEnumerable<Post>>(Arg.Do<string>(x => x = key)).Returns(expectedFromCache);
            postService.GetRSSDataAsync().Returns(expected);

            //Act
            var actual = await service.GenerateRSSData();

            //Assert
            Assert.Equal(expected, actual);
            logServiceProvider.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IEnumerable<Post>>(), Arg.Any<CacheItemOptions>());

        }

        [Fact]
        public async Task GenerateRSSData_WhenNotUsingCache_ReturnExpectedData()
        {

            var service = GetDataControlServiceNotUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<IEnumerable<Post>>();
            postService.GetRSSDataAsync().Returns(expected);

            //Act
            var actual = await service.GenerateRSSData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GetEuro2020PageViewModel_WhenCalled_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var expected = fixture.Create<Euro2020ViewModel>();
            fetchDataService.GetEuro2020ViewModel().Returns(expected);

            //Act
            var actual = await service.GetEuro2020PageViewModel();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GetBannerData_WhenCacheHaveData_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            var expected = fixture.Create<BannerModel>();
            cacheWrapperService.GetAsync<BannerModel>(Arg.Do<string>(x => x = key)).Returns(expected);
            //Act
            var actual = await service.GetBannerData();

            //Assert
            Assert.Equal(expected, actual);

        }

        [Fact]
        public async Task GetBannerData_WhenCacheReturnNull_ReturnExpectedData()
        {

            var service = GetDataControlServiceUsingCache();
            var key = fixture.Create<string>();
            BannerModel expectedFromCache = null;
            var expected = fixture.Create<BannerModel>();
            cacheWrapperService.GetAsync<BannerModel>(Arg.Do<string>(x => x = key)).Returns(expectedFromCache);
            bannerService.GetBannerInfoAsync().Returns(expected);

            //Act
            var actual = await service.GetBannerData();

            //Assert
            Assert.Equal(expected, actual);
            logServiceProvider.Received().WriteLogFileInfo(Arg.Any<string>());
            await cacheWrapperService.Received().SetAsync(Arg.Any<string>(), Arg.Any<BannerModel>(), Arg.Any<CacheItemOptions>());

        }

        [Fact]
        public async Task GetBannerData_WhenNotUsingCache_ReturnExpectedData()
        {

            var service = GetDataControlServiceNotUsingCache();
            var expected = fixture.Create<BannerModel>();
            bannerService.GetBannerInfoAsync().Returns(expected);

            //Act
            var actual = await service.GetBannerData();

            //Assert
            Assert.Equal(expected, actual);

        }

        #region Helpers
        private DataControlService GetDataControlServiceUsingCache()
        {
            var inMemorySettings = new Dictionary<string, string> {
                {"IsEnabledUseFanexCaching", "true"}
            };
            configuration = new ConfigurationBuilder()
                .AddInMemoryCollection(inMemorySettings)
                .Build();
            fetchDataService = Substitute.For<IFetchDataService>();
            cacheWrapperService = Substitute.For<ICacheWrapperService>();
            postService = Substitute.For<IPostService>();
            logServiceProvider = Substitute.For<ILogServiceProvider>();
            bannerService = Substitute.For<IBannerService>();

            var service = new DataControlService(fetchDataService, configuration, cacheWrapperService, postService, logServiceProvider, bannerService);

            return service;
        }

        private DataControlService GetDataControlServiceNotUsingCache()
        {
            var inMemorySettings = new Dictionary<string, string> {
                {"IsEnabledUseFanexCaching", "false"}
            };
            configuration = new ConfigurationBuilder()
                .AddInMemoryCollection(inMemorySettings)
                .Build();
            fetchDataService = Substitute.For<IFetchDataService>();
            cacheWrapperService = Substitute.For<ICacheWrapperService>();
            postService = Substitute.For<IPostService>();
            logServiceProvider = Substitute.For<ILogServiceProvider>();
            bannerService = Substitute.For<IBannerService>();

            var service = new DataControlService(fetchDataService, configuration, cacheWrapperService, postService, logServiceProvider, bannerService);

            return service;
        }

        #endregion Helpers
    }
}
