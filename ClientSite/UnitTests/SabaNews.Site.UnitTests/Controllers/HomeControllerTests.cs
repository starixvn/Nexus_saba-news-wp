namespace SabaNews.Site.UnitTests.Controllers
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Controllers;
    using SabaNews.Core.Services;
    using SabaNews.Models.Banner;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;
    using Xunit;
    using SabaNews.Core.Constants;
    using SabaNews.Models.Posts;
    using SabaNews.Models;
    using SabaNews.Criteria.Posts;
    using System.Collections.Generic;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc.Routing;

    public class HomeControllerTests : Controller
    {
        private IDataControlService dataControlService;
        private IPostService postService;
        private IConfiguration configuration;
        private IBannerService bannerService;
        private ILogServiceProvider logService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task Index_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var expectedViewModel = fixture.Create<PostViewModel>();
            var banner = fixture.Create<BannerModel>(); 

            dataControlService.GetHomePageViewModel().Returns(expectedViewModel);
            bannerService.GetBannerInfoAsync().Returns(banner);

            //Act
            var result = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PostViewModel>(viewResult.Model);
            Assert.Equal(banner, viewModel.Banner);
            Assert.Equal(expectedViewModel.Category.SEOTitle, viewModel.Header.Title);
        }

        [Fact]
        public async Task Index_WhenSeoTitleIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var expectedViewModel = fixture.Create<PostViewModel>();
            var banner = fixture.Create<BannerModel>();
            expectedViewModel.Category.SEOTitle = null;
            dataControlService.GetHomePageViewModel().Returns(expectedViewModel);
            bannerService.GetBannerInfoAsync().Returns(banner);

            //Act
            var result = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PostViewModel>(viewResult.Model);
            Assert.Equal(banner, viewModel.Banner);
            Assert.Equal(expectedViewModel.Category.Title, viewModel.Header.Title);
        }

        [Fact]
        public async Task Category_WhenSlugIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string slug = null;
            var page = fixture.Create<int>();
            var expectedViewModel = fixture.Create<CategoryViewModel>();
            dataControlService.GetCategoryViewModel(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => page = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Category(slug, page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<CategoryViewModel>(viewResult.Model);
            Assert.Equal(Constants.NewPostSlug, viewModel.Header.ActiveCategory);
            Assert.Equal(Constants.BlogView, viewResult.ViewName);
        }

        [Theory]
        [InlineData(Constants.FootballInside)]
        [InlineData(Constants.FootballOutside)]
        public async Task Category_WhenSlugIsFootballChildrens_ReturnExpectedViewResult(string slug)
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            var page = fixture.Create<int>();
            var expectedViewModel = fixture.Create<CategoryViewModel>();
            dataControlService.GetCategoryViewModel(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => page = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Category(slug, page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<CategoryViewModel>(viewResult.Model);
            Assert.True(viewModel.Header.IsChildren);
            Assert.Equal(Constants.FootballSlug, viewModel.Header.ActiveCategory);
            Assert.Equal(Constants.BlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Category_WhenViewDataIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            string slug = null;
            var page = fixture.Create<int>();
            CategoryViewModel expectedViewModel = null;
            dataControlService.GetCategoryViewModel(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => page = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Category(slug, page);

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
            var page = fixture.Create<int>();
            var expectedViewModel = fixture.Create<CategoryViewModel>();
            dataControlService.GetCategoryViewModel(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => page = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Video(page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<CategoryViewModel>(viewResult.Model);
            Assert.Equal(Constants.VideoView, viewResult.ViewName);
        }

        [Fact]
        public async Task Video_WhenDataIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            GetDefaultContext(controller);
            string slug = null;
            var page = fixture.Create<int>();
            CategoryViewModel expectedViewModel = null;
            dataControlService.GetCategoryViewModel(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => page = x)).Returns(expectedViewModel);

            //Act
            var result = await controller.Video(page);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task MatchSchedule_WhenHaveFullData_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            string slug = fixture.Create<string>();
            var type = fixture.Create<string>();
            var expectedPost = fixture.Create<Post>();
            var expectedPage = fixture.Create<Category>();
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<string>(x => type = x)).Returns(expectedPost);
            postService.GetPageDetailBySlugAsync(Arg.Do<string>(x => slug = x)).Returns(expectedPage);
            //Act
            var result = await controller.MatchSchedule();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);
            Assert.Equal(expectedPost, viewModel.Post);
            Assert.Equal(expectedPage, viewModel.Category);
            Assert.Equal(expectedPage.Description, viewModel.Header.Description);
        }

        [Fact]
        public async Task MatchSchedule_WhenDescriptionIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            string slug = fixture.Create<string>();
            var type = fixture.Create<string>();
            var expectedPost = fixture.Create<Post>();
            var expectedPage = fixture.Create<Category>();
            expectedPage.Description = null;
            expectedPage.SEOTitle = null;
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<string>(x => type = x)).Returns(expectedPost);
            postService.GetPageDetailBySlugAsync(Arg.Do<string>(x => slug = x)).Returns(expectedPage);
            //Act
            var result = await controller.MatchSchedule();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);
            Assert.Equal(expectedPost, viewModel.Post);
            Assert.Equal(expectedPage, viewModel.Category);
            Assert.Equal(expectedPage.Title, viewModel.Header.Description);
        }

        [Fact]
        public async Task Warning_WhenHaveFullData_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            string slug = fixture.Create<string>();
            var type = fixture.Create<string>();
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
            var viewModel = Assert.IsType<CategoryViewModel>(viewResult.Model);
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
            var viewModel = Assert.IsType<CategoryViewModel>(viewResult.Model);
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
            var viewModel = Assert.IsType<CategoryViewModel>(viewResult.Model);
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
            var viewModel = Assert.IsType<CategoryViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Posts);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }


        #region Helpers
        private HomeController GetController()
        {
            dataControlService = Substitute.For<IDataControlService>();
            postService = Substitute.For<IPostService>();
            configuration = Substitute.For<IConfiguration>();
            bannerService = Substitute.For<IBannerService>();
            logService = Substitute.For<ILogServiceProvider>();
            var controller = new HomeController(postService, dataControlService, configuration, bannerService, logService);

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
