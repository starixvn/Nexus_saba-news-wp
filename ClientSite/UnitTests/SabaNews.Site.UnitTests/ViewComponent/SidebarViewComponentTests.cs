namespace SabaNews.Site.UnitTests.ViewComponent
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc.ViewComponents;
    using NSubstitute;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;
    using SabaNews.Views.Shared.Components;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class SidebarViewComponentTests
    {
        private IPostService postService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task InvokeAsync_CateSlugIsNull_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var postId = fixture.Create<int>();
            string cateSlug = null;
            var model = fixture.Create<GetRecentPostsParam>();

            var expected = fixture.Create<IEnumerable<Post>>();
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => model = x)).Returns(expected);

            //Act
            var actual = await component.InvokeAsync(cateSlug, postId);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<SideBarViewModel>(viewResult.ViewData.Model);
            Assert.Equal(expected, viewModel.Posts);
        }

        [Fact]
        public async Task InvokeAsync_CateSlugIsNullAndPostIdIsZero_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var postId = 0;
            string cateSlug = null;
            var model = fixture.Create<GetRecentPostsParam>();

            var expected = fixture.Create<IEnumerable<Post>>();
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => model = x)).Returns(expected);

            //Act
            var actual = await component.InvokeAsync(cateSlug, postId);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<SideBarViewModel>(viewResult.ViewData.Model);
            Assert.Equal(expected, viewModel.Posts);
        }

        [Fact]
        public async Task InvokeAsync_CateSlugIsNotNull_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var postId = fixture.Create<int>();
            string cateSlug = fixture.Create<string>();
            var model = fixture.Create<PostParam>();

            var expected = fixture.Create<IEnumerable<Post>>();
            postService.GetTopPostsByCateSlugAsync(Arg.Do<PostParam>(x => model = x)).Returns(expected);

            //Act
            var actual = await component.InvokeAsync(cateSlug, postId);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<SideBarViewModel>(viewResult.ViewData.Model);
            Assert.Equal(expected, viewModel.Posts);
        }

        [Fact]
        public async Task InvokeAsync_CateSlugIsNotNullAndPostIdIsZero_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var postId = 0;
            string cateSlug = fixture.Create<string>();
            var model = fixture.Create<PostParam>();

            var expected = fixture.Create<IEnumerable<Post>>();
            postService.GetTopPostsByCateSlugAsync(Arg.Do<PostParam>(x => model = x)).Returns(expected);

            //Act
            var actual = await component.InvokeAsync(cateSlug, postId);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<SideBarViewModel>(viewResult.ViewData.Model);
            Assert.Equal(expected, viewModel.Posts);
        }

        #region Helpers
        private SidebarViewComponent GetViewComponent()
        {
            postService = Substitute.For<IPostService>();

            var component = new SidebarViewComponent(postService);

            return component;
        }

        #endregion Helpers
    }
}
