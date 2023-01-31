namespace SabaNews.Site.UnitTests.Controllers
{
    using System.Collections.Generic;
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Controllers;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;
    using Xunit;
    using SabaNews.Core.Constants;
    using SabaNews.Models.Posts;
    using SabaNews.Models;
    using SabaNews.Criteria.Posts;
    using SabaNews.Services;
    using Microsoft.AspNetCore.Mvc.Routing;
    using Microsoft.AspNetCore.Http;
    using SabaNews.Models.Home;

    public class HomeControllerTests : Controller
    {
        private IDataControlService dataControlService;
        private IPostService postService;
        private IConfiguration configuration;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task Index_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var expectedViewModel = fixture.Create<PostViewModel>();
            dataControlService.GetHomePageViewModel().Returns(expectedViewModel);

            //Act
            var result = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PostViewModel>(viewResult.Model);
            Assert.Equal(expectedViewModel.Category.SEOTitle, viewModel.Header.Title);
        }

        [Fact]
        public async Task Index_WhenSeoTitleIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var expectedViewModel = fixture.Create<PostViewModel>();
            expectedViewModel.Category.SEOTitle = null;
            dataControlService.GetHomePageViewModel().Returns(expectedViewModel);

            //Act
            var result = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PostViewModel>(viewResult.Model);
            Assert.Equal(expectedViewModel.Category.Title, viewModel.Header.Title);
        }

        [Fact]
        public async Task Category_WhenSlugIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string slug = null;
            var expectedViewModel = fixture.Create<BlogViewModel>();
            var param = fixture.Create<GetPostsByCateSlugsParam>();
            dataControlService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Category(slug, slug, slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.Null(viewModel.Header.ActiveCategory);
            Assert.Equal(Constants.BlogView, viewResult.ViewName);
        }

        [Theory]
        [InlineData(Constants.FootballInside)]
        [InlineData(Constants.FootballOutside)]
        [InlineData(Constants.FootballSlug)]
        public async Task Category_WhenSlugIsFootballChildrens_ReturnExpectedViewResult(string slug)
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            var expectedViewModel = fixture.Create<BlogViewModel>();
            var param = fixture.Create<GetPostsByCateSlugsParam>();
            dataControlService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Category(slug, slug, slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.True(viewModel.Header.IsChildren);
            Assert.Equal(slug, viewModel.Header.ActiveCategory);
            Assert.Equal(Constants.BlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Category_WhenViewDataIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            string slug = null;
            BlogViewModel expectedViewModel = null;
            var param = fixture.Create<GetPostsByCateSlugsParam>();
            dataControlService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Category(slug, slug, slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Video_WhenHaveData_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string slug = null;
            var expectedViewModel = fixture.Create<BlogViewModel>();
            var param = fixture.Create<GetPostsByCateSlugsParam>();
            dataControlService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Video(slug, slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.Equal(Constants.VideoView, viewResult.ViewName);
        }

        [Fact]
        public async Task Video_WhenPostIsEmpty_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string slug = null;
            var expectedViewModel = fixture.Create<BlogViewModel>();
            expectedViewModel.Posts = new List<Post>();
            var param = fixture.Create<GetPostsByCateSlugsParam>();
            dataControlService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Video(slug, slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Video_WhenDataIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string slug = null;
            var param = fixture.Create<GetPostsByCateSlugsParam>();
            BlogViewModel expectedViewModel = null;
            dataControlService.GetCategoryViewModel(Arg.Do<GetPostsByCateSlugsParam>(x => param = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Video(slug, slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Warning_WhenHaveFullData_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            string slug = fixture.Create<string>();
            var expectedPage = fixture.Create<Category>();
            postService.GetPageDetailBySlugAsync(Arg.Do<string>(x => slug = x)).Returns(expectedPage);
            //Act
            var result = await controller.Warning();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);
            Assert.Equal(expectedPage, viewModel.Category);
            Assert.Equal(expectedPage.Description, viewModel.Header.Description);
        }

        [Fact]
        public async Task Warning_WhenSeoTitleIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            string slug = fixture.Create<string>();
            var type = fixture.Create<string>();
            var expectedPage = fixture.Create<Category>();
            expectedPage.Description = null;
            expectedPage.SEOTitle = null;
            postService.GetPageDetailBySlugAsync(Arg.Do<string>(x => slug = x)).Returns(expectedPage);
            //Act
            var result = await controller.Warning();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);
            Assert.Equal(expectedPage, viewModel.Category);
            Assert.Equal(expectedPage.Title, viewModel.Header.Description);
        }

        [Fact]
        public void ChangeDomain_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
           
            //Act
            var result =  controller.ChangeDomain();

            // Assert
            Assert.IsType<ViewResult>(result);
           
        }

        [Fact]
        public async Task Search_WhenHaveFullData_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string keyword = fixture.Create<string>();
            var page = fixture.Create<int>();
            var criteria = fixture.Create<SearchPostsCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            postService.SearchPostAsync(Arg.Do<SearchPostsCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var result = await controller.Search(keyword, page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Posts);
            Assert.Equal(Constants.BlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Search_WhenDontHaveData_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string keyword = null;
            var page = fixture.Create<int>();
            var criteria = fixture.Create<SearchPostsCriteria>();
            IEnumerable<Post> expected = new List<Post>();
            postService.SearchPostAsync(Arg.Do<SearchPostsCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var result = await controller.Search(keyword, page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Posts);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Tag_WhenHaveFullData_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            controller.Url = Substitute.For<IUrlHelper>();
            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Scheme = fixture.Create<string>();
            string keyword = fixture.Create<string>();
            var page = fixture.Create<int>();
            var criteria = fixture.Create<GetPostsByTagCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            postService.GetPostByTagAsync(Arg.Do<GetPostsByTagCriteria>(x => criteria = x)).Returns(expected);
            configuration.GetValue<string>("BaseUrl").Returns("baseurl");
            //Act
            var result = await controller.Tag(keyword, page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Posts);
            Assert.Equal(Constants.BlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Tag_WhenPostIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            controller.Url = Substitute.For<IUrlHelper>();
            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Scheme = fixture.Create<string>();
            string keyword = fixture.Create<string>();
            var page = fixture.Create<int>();
            var criteria = fixture.Create<GetPostsByTagCriteria>();
            IEnumerable<Post> expected = new List<Post>();
            postService.GetPostByTagAsync(Arg.Do<GetPostsByTagCriteria>(x => criteria = x)).Returns(expected);
            configuration.GetValue<string>("BaseUrl").Returns("baseurl");
            //Act
            var result = await controller.Tag(keyword, page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<BlogViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Posts);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public void RedirectLivescorePage_WhenCalled_RedirectCorrectly()
        {
            // Arrange
            var controller = GetController();

            // Act
            var result = controller.RedirectLivescorePage();

            // Assert
            var redirectResult = Assert.IsType<RedirectResult>(result);
            Assert.Equal("/lich-thi-dau/bong-da", redirectResult.Url);
        }


        [Fact]
        public async Task GetCategoryGrid_WhenSlugIsVideoCategory_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var criteria = fixture.Create<GetPostsByCateSlugsPagingCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            var param = fixture.Create<CategoryPostParams>();
            param.IsVideo = true;
            param.Page = 2;
            postService.GetPostsByCateSlugsPaging(Arg.Do<GetPostsByCateSlugsPagingCriteria>(x => criteria = x)).Returns(expected);
            // Act
            var result = await controller.GetCategoryGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<IEnumerable<Post>>(viewResult.Model);
            Assert.Equal(expected, viewModel);
            Assert.Equal("~/Views/Home/_VideoGridView.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task GetCategoryGrid_WhenSlugIsPostsCategory_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var criteria = fixture.Create<GetPostsByCateSlugsPagingCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            var param = fixture.Create<CategoryPostParams>();
            param.IsVideo = false;
            postService.GetPostsByCateSlugsPaging(Arg.Do<GetPostsByCateSlugsPagingCriteria>(x => criteria = x)).Returns(expected);
            // Act
            var result = await controller.GetCategoryGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<IEnumerable<Post>>(viewResult.Model);
            Assert.Equal(expected, viewModel);
            Assert.Equal("~/Views/Home/_CategoryGridView.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task GetCategoryGrid_WhenSlugIsNull_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var criteria = fixture.Create<GetRecentPostsParam>();
            var expected = fixture.Create<IEnumerable<Post>>();
            var param = fixture.Create<CategoryPostParams>();
            param.Slugs.Slug1 = null;
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => criteria = x)).Returns(expected);
            // Act
            var result = await controller.GetCategoryGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<IEnumerable<Post>>(viewResult.Model);
            Assert.Equal(expected, viewModel);
        }

        [Fact]
        public async Task GetCategoryGrid_WhenPostsIsNull_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var criteria = fixture.Create<GetRecentPostsParam>();
            var expected = new List<Post>();
            var param = fixture.Create<CategoryPostParams>();
            param.Slugs.Slug1 = null;
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => criteria = x)).Returns(expected);
            // Act
            var result = await controller.GetCategoryGrid(param);

            // Assert
            var okResult = Assert.IsAssignableFrom<OkObjectResult>(result);
            Assert.False((bool)okResult.Value);
        }

        [Fact]
        public async Task GetTypePostsGrid_WhenIsSearchType_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var criteria = fixture.Create<SearchPostsCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            var param = fixture.Create<TypePostParam>();
            param.Type = Constants.Search;
            postService.SearchPostAsync(Arg.Do<SearchPostsCriteria>(x => criteria = x)).Returns(expected);
            // Act
            var result = await controller.GetTypePostsGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<IEnumerable<Post>>(viewResult.Model);
            Assert.Equal(expected, viewModel);
            Assert.Equal("~/Views/Home/_CategoryGridView.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task GetTypePostsGrid_WhenIsTagType_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var criteria = fixture.Create<GetPostsByTagCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            var param = fixture.Create<TypePostParam>();
            param.Type = Constants.Tag;
            postService.GetPostByTagAsync(Arg.Do<GetPostsByTagCriteria>(x => criteria = x)).Returns(expected);
            // Act
            var result = await controller.GetTypePostsGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<IEnumerable<Post>>(viewResult.Model);
            Assert.Equal(expected, viewModel);
            Assert.Equal("~/Views/Home/_CategoryGridView.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task GetTypePostsGrid_WhenPostsIsNull_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var criteria = fixture.Create<SearchPostsCriteria>();
            var expected = new List<Post>();
            var param = fixture.Create<TypePostParam>();
            param.Type = Constants.Search;
            postService.SearchPostAsync(Arg.Do<SearchPostsCriteria>(x => criteria = x)).Returns(expected);
            // Act
            var result = await controller.GetTypePostsGrid(param);

            // Assert
            var okResult = Assert.IsAssignableFrom<OkObjectResult>(result);
            Assert.False((bool)okResult.Value);
        }

        [Fact]
        public async Task GetHomeFixture_WhenCalled_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var expected = fixture.Create<List<MatchLeagueModel>>();
            dataControlService.GetHomepageFixtureData().Returns(expected);
            // Act
            var result = await controller.GetHomeFixture();

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expected, jsonResult.Value);
        }

        [Fact]
        public async Task GetHomeStanding_WhenCalled_ReturnCorrectlyData()
        {
            // Arrange
            var controller = GetController();
            var expected = fixture.Create<List<StandingLeagueModel>>();
            dataControlService.GetHomepageStandingData().Returns(expected);
            // Act
            var result = await controller.GetHomeStanding();

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expected, jsonResult.Value);
        }

        #region Helpers
        private HomeController GetController()
        {
            dataControlService = Substitute.For<IDataControlService>();
            postService = Substitute.For<IPostService>();
            configuration = Substitute.For<IConfiguration>();
            var controller = new HomeController(postService, dataControlService, configuration);

            return controller;
        }

        private void GetDefaultContext(HomeController controller)
        {
            var urlHelperMock = Substitute.For<IUrlHelper>();
            controller.Url = urlHelperMock;
            urlHelperMock.Action(Arg.Any<UrlActionContext>()).Returns("url");
            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Scheme = fixture.Create<string>();
        }
        #endregion Helpers
    }
}
