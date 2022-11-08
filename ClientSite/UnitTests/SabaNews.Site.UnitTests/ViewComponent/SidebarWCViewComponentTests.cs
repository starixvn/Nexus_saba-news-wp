namespace SabaNews.Site.UnitTests.ViewComponent
{
    using System.Threading.Tasks;
    using System.Collections.Generic;
    using SabaNews.Services;
    using AutoFixture;
    using Xunit;
    using SabaNews.Models.Posts;
    using NSubstitute;
    using Microsoft.AspNetCore.Mvc.ViewComponents;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Site.Views.Event.WorldCup2022.Sidebar;

    public class SidebarWCViewComponentTests
    {
        private IPostService postService;
        private readonly IFixture fixture = new Fixture();


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
        private SidebarWCViewComponent GetViewComponent()
        {
            postService = Substitute.For<IPostService>();

            var component = new SidebarWCViewComponent(postService);

            return component;
        }

        #endregion Helpers
    }
}
