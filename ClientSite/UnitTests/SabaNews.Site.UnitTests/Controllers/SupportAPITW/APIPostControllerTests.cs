using AutoFixture;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Options;
using NSubstitute;
using SabaNews.Core.Constants;
using SabaNews.Core.Models;
using SabaNews.Core.Services;
using SabaNews.Models.Posts;
using SabaNews.Models.ViewModel.Posts;
using SabaNews.Services;
using SabaNews.Site.Controllers.SupportAPITW;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Site.UnitTests.Controllers.SupportAPITW
{
    public class APIPostControllerTests : Controller
    {
        private IPostService postService;
        private ICacheWrapperService cacheService;
        private readonly IFixture fixture = new Fixture();


        [Fact]
        public async Task GetPostList_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<GetDataViewModel>();
            var expectedPosts = fixture.Create<IList<GetData>>();
            var cacheKey = string.Format("{0}{1}{2}{3}", CacheKey.GetPostList, param.LastTimestamp, param.Category, param.Subject);

            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Headers["Token"] = Constants.TokenAPIGetData;

            postService.GetPostlist(Arg.Do<GetDataViewModel>(x => param = x)).Returns(expectedPosts);
            cacheService.GetAsync<IList<GetData>>(cacheKey).Returns(expectedPosts);
            //Act
            var result = await controller.GetPostList(param);

            // Assert
            var okResult = Assert.IsType<JsonResult>(result);
            var posts = Assert.IsAssignableFrom<IList<GetData>>(okResult.Value);
            Assert.Equal(expectedPosts, posts);
        }

        [Fact]
        public async Task GetDetailContent_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<GetDataViewModel>();
            var expectedPosts = fixture.Create<GetDetailContent>();
            var cacheKey = string.Format("{0}{1}", CacheKey.GetPostDetailContent, param.PostId);

            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Headers["Token"] = Constants.TokenAPIGetData;

            postService.GetPostDetailContent(Arg.Do<GetDataViewModel>(x => param = x)).Returns(expectedPosts);
            cacheService.GetAsync<GetDetailContent>(cacheKey).Returns(expectedPosts);
            //Act
            var result = await controller.GetDetailContent(param);

            // Assert
            var okResult = Assert.IsType<JsonResult>(result);
            var posts = Assert.IsAssignableFrom<GetDetailContent>(okResult.Value);
            Assert.Equal(expectedPosts, posts);
        }

        #region Helpers
        private APIPostController GetController(bool enabledCache = true)
        {
            var cacheConfigs = new LivescoreMainPageCacheConfig
            {
                Enabled = enabledCache,
                DateRange = 2,
                OutOfRangeTimeout = 5
            };
            var options = Substitute.For<IOptionsSnapshot<LivescoreMainPageCacheConfig>>();
            options.Value.Returns(cacheConfigs);
            postService = Substitute.For<IPostService>();
            cacheService = Substitute.For<ICacheWrapperService>();
            var controller = new APIPostController(postService, options, cacheService);

            return controller;
        }
        #endregion Helpers
    }
}
