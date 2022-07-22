namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;
    using SabaNews.Core.Constants;
    using SabaNews.Criteria.Posts;
    using System.Globalization;

    public class FetchDataServiceTests
    {
        private IPostService postService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task GetHomePageViewModel_WhenCalled_ReturnExpectedPostViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = fixture.Create<string>();
            var first = fixture.Create<int>();
            var end = fixture.Create<int>();
            
            var paramGetRecent = fixture.Create<GetRecentPostsParam>();
            var newPosts = fixture.Create<IEnumerable<Post>>();
            var posts = fixture.Create<IEnumerable<Post>>();
            var category = fixture.Create<Category>(); 
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => paramGetRecent = x)).Returns(newPosts);
            postService.GetPostsBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => first = x), Arg.Do<int>(x => end = x)).Returns(posts);
            postService.GetPageDetailBySlugAsync(Arg.Do<string>(x => slug = x)).Returns(category);
            
            //Act
            var actual = await service.GetHomePageViewModel();

            //Assert
            Assert.Equal(newPosts.ToArray(), actual.NewPosts);
            Assert.Equal(posts.ToArray(), actual.FootballPosts);
            Assert.Equal(posts.ToArray(), actual.OtherPosts);
            Assert.Equal(posts.ToArray(), actual.TennisPosts);
            Assert.Equal(posts.ToArray(), actual.VideoPosts);
            Assert.Equal(posts.ToArray(), actual.GuessPosts);
            Assert.Equal(posts.ToArray(), actual.BackstagePosts);
            Assert.Equal(posts.ToArray(), actual.TransferPosts);
            Assert.Equal(category, actual.Category);
        }

        [Fact]
        public async Task GetCategoryViewModel_WhenCallVideoCategory_ReturnExpectedCategoryViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = Constants.VideoSlug;
            var page = fixture.Create<int>();
            var param = new GetPostsByCateSlugsParam { Slug1 = Constants.VideoSlug };

            var paramGetPost = fixture.Create<GetPostsByCateSlugCriteria>();
            var newPosts = fixture.Create<IEnumerable<Post>>();
            var posts = fixture.Create<IEnumerable<Post>>();
            var category = fixture.Create<Category>();
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => paramGetPost = x)).Returns(posts);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);

            //Act
            var actual = await service.GetCategoryViewModel(param);

            //Assert
            Assert.Equal(posts.ToArray(), actual.Posts);
            Assert.Equal(category, actual.Category);
        }

        [Fact]
        public async Task GetCategoryViewModel_WhenSlugIsNull_ReturnExpectedCategoryViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = Constants.NewPostSlug;
            var page = fixture.Create<int>();
            var paramGetView = new GetPostsByCateSlugsParam();
            var param = fixture.Create<GetRecentPostsParam>();
            var newPosts = fixture.Create<IEnumerable<Post>>();
            var posts = fixture.Create<IEnumerable<Post>>();
            var category = new Category { CateId = 1, Description = "description", Title = "title" };
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => param = x)).Returns(posts);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);

            //Act
            var actual = await service.GetCategoryViewModel(paramGetView);

            //Assert
            Assert.Equal(posts.ToArray(), actual.Posts);
            Assert.Equal(category, actual.Category);
            Assert.Equal(category.Title, actual.Header.HeadTitle);
            Assert.Equal(category.Description, actual.Header.Description);
            Assert.Equal(category.Title, actual.Header.Title);
            Assert.Equal(category.CateId, actual.Header.CategoryId);
        }

        [Fact]
        public async Task GetCategoryViewModel_WhenPostsIsNull_ReturnExpectedCategoryViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = Constants.Video;
            var page = fixture.Create<int>();
            var paramGetView = fixture.Create<GetPostsByCateSlugsParam>();

            var param = fixture.Create<GetPostsByCateSlugCriteria>();
            var newPosts = fixture.Create<IEnumerable<Post>>();
            var category = fixture.Create<Category>();
            IEnumerable<Post> posts = new List<Post>();
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => param = x)).Returns(posts);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);

            //Act
            var actual = await service.GetCategoryViewModel(paramGetView);

            //Assert
            Assert.Null(actual);
        }

        [Fact]
        public async Task GetMenuData_WhenCalled_ReturnExpectedMenus()
        {
            //Arrange
            var service = GetFetchDataService();
            var menuName = fixture.Create<string>();

            var expected = fixture.Create<IEnumerable<Menu>>();
            postService.GetMenuAsync(Arg.Do<string>(x => menuName = x)).Returns(expected);

            //Act
            var actual = await service.GetMenuData();

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetEuro2020ViewModel_WhenCalled_ReturnExpectedEuro2020ViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = fixture.Create<string>();
            var first = fixture.Create<int>();
            var end = fixture.Create<int>();
            var type = fixture.Create<string>();
            var posts = fixture.Create<IEnumerable<Post>>();
            var detail = fixture.Create<Post>();
            var category = fixture.Create<Category>();
            postService.GetPostsBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => first = x), Arg.Do<int>(x => end = x)).Returns(posts);
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<string>(x => type = x)).Returns(detail);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);
            var ranking = posts.Where(item => item.PostExcerpt.Trim().ToLower(CultureInfo.InvariantCulture) != Constants.TemplatePost).OrderBy(item => item.PostDate).ToList();
            //Act
            var actual = await service.GetEuro2020ViewModel();

            //Assert
            Assert.Equal(detail, actual.TotalRanking);
            Assert.Equal(ranking.ToArray(), actual.Ranking);
            Assert.Equal(posts.ToArray(), actual.HotNews);
            Assert.Equal(posts.ToArray(), actual.Videos);
            Assert.Equal(posts.ToArray(), actual.Identify);
            Assert.Equal(category, actual.Category);
            Assert.Equal(category.Title, actual.ViewData.HeadTitle);
            Assert.Equal(category.SEOTitle, actual.ViewData.Title);
            Assert.Equal(category.Description, actual.ViewData.Description);
        }

        [Fact]
        public async Task GetEuro2020ViewModel_WhenCategoryNotHaveSeoTitle_ReturnExpectedEuro2020ViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = fixture.Create<string>();
            var first = fixture.Create<int>();
            var end = fixture.Create<int>();
            var type = fixture.Create<string>();
            var posts = fixture.Create<IEnumerable<Post>>();
            var detail = fixture.Create<Post>();
            var category = new Category { CateId = 1, Description = "description", Title = "title" };
            postService.GetPostsBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => first = x), Arg.Do<int>(x => end = x)).Returns(posts);
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<string>(x => type = x)).Returns(detail);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);
            var ranking = posts.Where(item => item.PostExcerpt.Trim().ToLower(CultureInfo.InvariantCulture) != Constants.TemplatePost).OrderBy(item => item.PostDate).ToList();
            //Act
            var actual = await service.GetEuro2020ViewModel();

            //Assert
            Assert.Equal(detail, actual.TotalRanking);
            Assert.Equal(ranking.ToArray(), actual.Ranking);
            Assert.Equal(posts.ToArray(), actual.HotNews);
            Assert.Equal(posts.ToArray(), actual.Videos);
            Assert.Equal(posts.ToArray(), actual.Identify);
            Assert.Equal(category, actual.Category);
            Assert.Equal(category.Title, actual.ViewData.HeadTitle);
            Assert.Equal(category.Title, actual.ViewData.Title);
            Assert.Equal(category.Description, actual.ViewData.Description);
        }

        #region Helpers
        private FetchDataService GetFetchDataService()
        {
            postService = Substitute.For<IPostService>();

            var service = new FetchDataService(postService);

            return service;
        }
        #endregion Helpers
    }
}
