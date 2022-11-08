namespace SabaNews.Site.UnitTests.ViewComponent
{
    using System.Threading.Tasks;
    using System.Collections.Generic;
    using SabaNews.Services;
    using Xunit;
    using AutoFixture;
    using SabaNews.Models.Posts;
    using NSubstitute;
    using Microsoft.AspNetCore.Mvc.ViewComponents;
    using SabaNews.Criteria.Posts;
    using SabaNews.Site.Views.Event.WorldCup2022.RelatedPosts;

    public class RelatedPostsWCViewComponentTests
    {
        private IPostService postService;
        private readonly IFixture fixture = new Fixture();


        [Fact]
        public async Task InvokeAsync_WhenNotHavePostInList_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var fixture = new Fixture();
            var postId = 50;
            var slug = fixture.Create<string>();
            var model = fixture.Create<GetPostsByCateSlugCriteria>();
            model._End = 3;
            var expected = new List<Post>();
            expected.Add(new Post { ID = 1 });
            expected.Add(new Post { ID = 2 });
            expected.Add(new Post { ID = 3 });
            expected.Add(new Post { ID = 4 });
            expected.Add(new Post { ID = 5 });
            expected.Add(new Post { ID = 6 });
            expected.Add(new Post { ID = 7 });
            expected.Add(new Post { ID = 8 });
            expected.Add(new Post { ID = 9 });
            expected.Add(new Post { ID = 10 });
            expected.Add(new Post { ID = 11 });
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => model = x)).Returns(expected);

            //Act
            var actual = await component.InvokeAsync(postId);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            expected.RemoveAt(10);
            Assert.Equal(expected, viewResult.ViewData.Model);
        }

        [Fact]
        public async Task InvokeAsync_WhenHavePostInList_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var fixture = new Fixture();
            var postId = 5;
            var slug = fixture.Create<string>();
            var model = fixture.Create<GetPostsByCateSlugCriteria>();
            model._End = 3;
            var expected = new List<Post>();
            expected.Add(new Post { ID = 1 });
            expected.Add(new Post { ID = 2 });
            expected.Add(new Post { ID = 3 });
            expected.Add(new Post { ID = 4 });
            expected.Add(new Post { ID = 5 });
            expected.Add(new Post { ID = 6 });
            expected.Add(new Post { ID = 7 });
            expected.Add(new Post { ID = 8 });
            expected.Add(new Post { ID = 9 });
            expected.Add(new Post { ID = 10 });
            expected.Add(new Post { ID = 11 });
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => model = x)).Returns(expected);

            //Act
            var actual = await component.InvokeAsync(postId);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            expected.RemoveAt(4);
            Assert.Equal(expected, viewResult.ViewData.Model);
        }

        #region Helpers
        private RelatedPostsWCViewComponent GetViewComponent()
        {
            postService = Substitute.For<IPostService>();

            var component = new RelatedPostsWCViewComponent(postService);

            return component;
        }

        #endregion Helpers
    }
}
