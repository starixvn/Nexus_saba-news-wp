namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using SabaNews.Criteria.Posts;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models;
    using SabaNews.Models.Home;
    using SabaNews.Models.Options;
    using SabaNews.Models.Posts;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;

    public class FetchDataServiceTests
    {
        private IPostService postService;
        private IConfiguration configuration;
        private IMatchService matchService;
        private ILeagueService leagueService;
        private IOptionService optionService;
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
            // var actual = await service.GetHomePageViewModel();

            //Assert
            // Assert.Equal(newPosts.ToArray(), actual.NewPosts);
            // Assert.Equal(posts.ToArray(), actual.FootballPosts);
            // Assert.Equal(posts.ToArray(), actual.OtherPosts);
            // Assert.Equal(posts.ToArray(), actual.TennisPosts);
            // Assert.Equal(posts.ToArray(), actual.VideoPosts);
            // Assert.Equal(posts.ToArray(), actual.GuessPosts);
            // Assert.Equal(posts.ToArray(), actual.BackstagePosts);
            // Assert.Equal(posts.ToArray(), actual.TransferPosts);
            // Assert.Equal(category, actual.Category);
        }

        [Fact]
        public async Task GetCategoryViewModel_WhenCallVideoCategory_ReturnExpectedCategoryViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = Constants.VideoSlug;
            var page = fixture.Create<int>();
            var criteria = fixture.Create<GetPostsByCateSlugsCriteria>();

            var param = fixture.Create<GetPostsByCateSlugsParam>();
            param.Slug1 = Constants.VideoSlug;
            var newPosts = fixture.Create<IEnumerable<Post>>();
            var posts = fixture.Create<IEnumerable<Post>>();
            var category = fixture.Create<Category>();
            postService.GetPostsByCateSlugs(Arg.Do<GetPostsByCateSlugsCriteria>(x => criteria = x)).Returns(posts);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);

            //Act
            var actual = await service.GetCategoryViewModel(param);

            //Assert
            Assert.Equal(posts.ToArray(), actual.Posts);
            Assert.Equal(category, actual.Category);
        }

        [Fact]
        public async Task GetCategoryViewModel_WhenCallNewPostSlug_ReturnExpectedCategoryViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = fixture.Create<string>();
            var param = new GetPostsByCateSlugsParam();

            var paramGetRecent = fixture.Create<GetRecentPostsParam>();
            var newPosts = fixture.Create<IEnumerable<Post>>();
            var posts = fixture.Create<IEnumerable<Post>>();
            var category = new Category { CateId = 1, Description = "description", Title = "title" };
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => paramGetRecent = x)).Returns(posts);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);

            //Act
            var actual = await service.GetCategoryViewModel(param);

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
            var slug = Constants.VideoSlug;
            var param = fixture.Create<GetPostsByCateSlugsParam>();

            var criteria = fixture.Create<GetPostsByCateSlugsCriteria>();
            var newPosts = fixture.Create<IEnumerable<Post>>();
            var category = fixture.Create<Category>();
            IEnumerable<Post> posts = new List<Post>();
            postService.GetPostsByCateSlugs(Arg.Do<GetPostsByCateSlugsCriteria>(x => criteria = x)).Returns(posts);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);

            //Act
            var actual = await service.GetCategoryViewModel(param);

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

        [Fact]
        public async Task GetWorldCupViewModel_WhenCalled_ReturnExpectedPostViewModel()
        {
            //Arrange
            var service = GetFetchDataService();
            var slug = fixture.Create<string>();
            var first = fixture.Create<int>();
            var end = fixture.Create<int>();

            var posts = fixture.Create<IEnumerable<Post>>();
            var category = fixture.Create<Category>();
            postService.GetPostsBySlugAsync(Arg.Do<string>(x => slug = x), Arg.Do<int>(x => first = x), Arg.Do<int>(x => end = x)).Returns(posts);
            postService.GetCateDetailAsync(Arg.Do<string>(x => slug = x)).Returns(category);

            //Act
            var actual = await service.GetWorldCupViewModel();

            //Assert
            Assert.Equal(posts, actual.LastestNews);
            Assert.Equal(posts, actual.Commentary);
            Assert.Equal(posts, actual.Videos);
            Assert.Equal(category, actual.Category);
        }

        [Fact]
        public async Task GetWCFixtureData_WhenCalled_ReturnExpectedData()
        {
            //Arrange
            var service = GetFetchDataService();
            var criteria = fixture.Create<GetMatchBySeasonCriteria>();

            var expected = fixture.Create<(League, IList<Match>)>();
            matchService.GetMatchBySeasonAsync(Arg.Do<GetMatchBySeasonCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetWCFixtureData();

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetHomepageFixture_WhenCalled_ReturnExpectedData()
        {
            //Arrange
            var service = GetFetchDataService();
            var criteria = fixture.Create<GetMatchBySeasonCriteria>();
            var option = new Option
            {
                OptionId = 1,
                OptionValue = "[{\"Id\":1,\"LeagueId\":12002,\"Order\":1, \"SeasonId\":0}]"
            };
            var expectedOptionList = Helper.ParseJsonToList<FixtureHomepage>(option.OptionValue);
            var expected = fixture.Create<(League, IList<Match>)>();
            var expectedResult = new List<MatchLeagueModel>();
            expectedResult.Add(new MatchLeagueModel{League = expected.Item1, Matches = expected.Item2});
            expectedResult.Add(new MatchLeagueModel{League = expected.Item1, Matches = expected.Item2});
            optionService.GetOptionAsync(Arg.Do<string>(x =>  x = "key")).Returns(option);
            matchService.GetMatchBySeasonAsync(Arg.Do<GetMatchBySeasonCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetHomepageFixture();

            //Assert
            Assert.Equal(expected.Item1, actual.First().League);
            Assert.Equal(expected.Item2, actual.First().Matches);
        }

        [Fact]
        public async Task GetHomepageStanding_WhenCalled_ReturnExpectedData()
        {
            //Arrange
            var service = GetFetchDataService();
            var criteria = fixture.Create<GetLeagueAndSeasonStandingsCriteria>();
            var option = new Option
            {
                OptionId = 1,
                OptionValue = "[{\"Id\":1,\"LeagueId\":12002,\"Order\":1, \"SeasonId\":0}]"
            };
            var expectedOptionList = Helper.ParseJsonToList<FixtureHomepage>(option.OptionValue);
            var expected = fixture.Create<(League, IList<SeasonStandings>)>();
            var expectedResult = new List<MatchLeagueModel>();
            optionService.GetOptionAsync(Arg.Do<string>(x =>  x = "key")).Returns(option);
            leagueService.GetLeagueAndSeasonStandingAsync(Arg.Do<GetLeagueAndSeasonStandingsCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetHomepageStanding();

            //Assert
            Assert.Equal(expected.Item1, actual.First().League);
            Assert.Equal(expected.Item2, actual.First().Standing);
        }


        #region Helpers
        private FetchDataService GetFetchDataService()
        {
            var MenuNameSettings = new Dictionary<string, string> {
                {"MenuName", "MenuName"}
            };
            configuration = new ConfigurationBuilder()
                .AddInMemoryCollection(MenuNameSettings)
                .Build();
            postService = Substitute.For<IPostService>();
            matchService = Substitute.For<IMatchService>();
            leagueService = Substitute.For<ILeagueService>();
            optionService = Substitute.For<IOptionService>();

            var service = new FetchDataService(postService, configuration, matchService, leagueService, optionService);

            return service;
        }
        #endregion Helpers
    }
}
