namespace SabaNews.Site.UnitTests.Controllers.Event
{
    using AutoFixture;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.AspNetCore.Mvc.Routing;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Posts;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Categories;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Controllers.Event;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;

    public class WorldCup2022ControllerTests : Controller
    {
        private IDataControlService dataControlService;
        private IPostService postService;
        private IMatchService matchService;
        private ILeagueService leagueService;
        private IConfiguration configuration;
        private ISessionService sessionService;
        private IUserService userService;
        private readonly IFixture fixture = new Fixture();


        [Fact]
        public async Task Index_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var expected = new Fixture().Create<WorldCupViewModel>();
            dataControlService.GetWorldCupPageViewModel().Returns(expected);

            //Act
            var result = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/Index.cshtml", viewResult.ViewName);
            var dataResult = Assert.IsType<WorldCupViewModel>(viewResult.Model);
            Assert.Equal(expected, dataResult);

        }

        [Fact]
        public async Task Index_WhenCateDontHaveSeoData_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var expected = new Fixture().Create<WorldCupViewModel>();
            expected.Category.SEOTitle = null;
            dataControlService.GetWorldCupPageViewModel().Returns(expected);

            //Act
            var result = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/Index.cshtml", viewResult.ViewName);
            var dataResult = Assert.IsType<WorldCupViewModel>(viewResult.Model);
            Assert.Equal(expected, dataResult);

        }

        [Fact]
        public void Fixture_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();

            //Act
            var result = controller.Fixture();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/Fixture.cshtml", viewResult.ViewName);

        }

        [Fact]
        public async Task GetLastestNews_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var news = new Fixture().Create<IEnumerable<Post>>();
            var cateSlugCriteria = new Fixture().Create<GetPostsByCateSlugCriteria>(); 
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.LastestNews();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/LastestNewsWC.cshtml", viewResult.ViewName);
            var newsResult = Assert.IsType<LastestNewsViewModel>(viewResult.Model);
            Assert.Equal(news, newsResult.Posts);

        }
              
        [Fact]
        public async Task GetCommentaryNews_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var news = new Fixture().Create<IEnumerable<Post>>();
            var cateSlugCriteria = new Fixture().Create<GetPostsByCateSlugCriteria>();
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.CommentaryNews();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/LastestNewsWC.cshtml", viewResult.ViewName);
            var newsResult = Assert.IsType<LastestNewsViewModel>(viewResult.Model);
            Assert.Equal(news, newsResult.Posts);

        }

        [Fact]
        public async Task GetVideo_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var news = new Fixture().Create<IEnumerable<Post>>();
            var cateSlugCriteria = new Fixture().Create<GetPostsByCateSlugCriteria>();
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.Video();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/Video.cshtml", viewResult.ViewName);
            var newsResult = Assert.IsType<VideoViewModel>(viewResult.Model);
            Assert.Equal(news, newsResult.Videos);
        }

        [Fact]
        public async Task GetStanding_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var news = new Fixture().Create<IList<SeasonStandings>>();
            var cateSlugCriteria = new Fixture().Create<GetLeagueSeasonStandingCriteria>();
            leagueService.GetLeagueSeasonStanding(Arg.Do<GetLeagueSeasonStandingCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.Standing();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/DetailStanding.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task GetNewsGrid_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = new Fixture().Create<CategoryPostParams>();
            var news = new Fixture().Create<IEnumerable<Post>>();
            var cateSlugCriteria = new Fixture().Create<GetPostsByCateSlugCriteria>();

            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.GetNewsGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            if (!param.IsVideo){
                Assert.Equal("~/Views/Event/WorldCup2022/_NewsGridView.cshtml", viewResult.ViewName);
            }
            else{
                Assert.Equal("~/Views/Event/WorldCup2022/_VideoGridView.cshtml", viewResult.ViewName);
            }
        }

        [Fact]
        public async Task GetStandings_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var standings = new Fixture().Create<IList<SeasonStandings>>();
            var standingsCriteria = new Fixture().Create<GetLeagueSeasonStandingCriteria>();
            leagueService.GetLeagueSeasonStanding(Arg.Do<GetLeagueSeasonStandingCriteria>(x => standingsCriteria = x)).Returns(standings);
            var standingGroup = controller.GroupStandingsByGroupName(standings);
            //Act
            var result = await controller.GetStandings();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/_Standing.cshtml", viewResult.ViewName);
            var standingResult = Assert.IsType<List<StandingViewModel>>(viewResult.Model);
            Assert.Equal(standingGroup.Count, standingResult.Count);
        }

        [Fact]
        public async Task GetTopScorers_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var topScorers = new Fixture().Create<IList<TopScorers>>();
            var topCriteria = new Fixture().Create<GetTop10ScorersCriteria>();
            leagueService.GetTop10ScorersAsync(Arg.Do<GetTop10ScorersCriteria>(x => topCriteria = x)).Returns(topScorers);

            //Act
            var result = await controller.GetTopScorers();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/WorldCup2022/_TopScorers.cshtml", viewResult.ViewName);
            var topResult = Assert.IsAssignableFrom<IList<TopScorers>>(viewResult.Model);
            Assert.Equal(topScorers, topResult);
        }

        [Fact]
        public void GetGroupNames_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var seasonStandings = new Fixture().Create<IList<SeasonStandings>>();

            //Act
            var result = controller.GetGroupNames(seasonStandings);

            // Assert
            Assert.IsAssignableFrom<List<string>>(result);
        }

        [Fact]
        public void GroupStandingsByGroupName_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var seasonStandings = new Fixture().Create<IList<SeasonStandings>>();

            //Act
            var result = controller.GroupStandingsByGroupName(seasonStandings);

            // Assert
            Assert.IsAssignableFrom<List<StandingViewModel>>(result);
        }

        [Fact]
        public void GetRelatedPosts_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var postId = fixture.Create<int>();
            var slug = fixture.Create<string>();

            //Act
            var result = controller.GetRelatedPostsWC(slug, postId);

            // Assert
            Assert.IsType<ViewComponentResult>(result);
        }

        [Fact]
        public void GetSidebarPosts_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var postId = fixture.Create<int>();
            var slug = fixture.Create<string>();

            //Act
            var result = controller.GetSidebarPostsWC(slug, postId);

            // Assert
            Assert.IsType<ViewComponentResult>(result);
        }

        [Fact]
        public async Task Single_WhenPostIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            Post expected = null;

            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);

            //Act
            var result = await controller.PostDetailWC(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Single_WhenAuthencated_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            var param = fixture.Create<GetRecentPostsParam>();
            Post expected = fixture.Create<Post>();
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new CategoryGeneral { Id = 1, Slug = "tin-the-thao" },
                new CategoryGeneral { Id = 2, Slug = "bong-da" }
            };
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expectedUser.SavedPosts = null;
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.PostDetailWC(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Null(viewModel.ViewData);
        }

        [Fact]
        public async Task Single_WhenIsVideoPost_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            var param = fixture.Create<GetRecentPostsParam>();
            Post expected = fixture.Create<Post>();
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new CategoryGeneral { Id = 1, Slug = "tin-the-thao" },
                new CategoryGeneral { Id = 2, Slug = "video" }
            };
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expected.Type = Constants.VideoPostType;
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.PostDetailWC(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.True(viewModel.IsVideo);
        }

        [Theory]
        [InlineData(Constants.CollectionSlug)]
        [InlineData(Constants.BasketballSlug)]
        [InlineData(Constants.TennisSlug)]
        public async Task Single_PostInOtherCategory_ReturnExpectedViewResult(string cateSlug)
        {
            // Arrange
            var controller = GetController();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            var param = fixture.Create<GetRecentPostsParam>();
            Post expected = fixture.Create<Post>();
            expected.CatesName = cateSlug;
            expected.CategoriesSlug = cateSlug;
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
            var expectedUser = fixture.Create<UserSession>();
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new CategoryGeneral { Id = 1, Slug = "tin-the-thao" },
                new CategoryGeneral { Id = 2, Slug = cateSlug }
            };
            sessionService.User = fixture.Create<UserSession>();
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";

            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.PostDetailWC(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(Constants.OtherPostSlug, viewModel.CateInfo.Slug);
            Assert.Equal($"/tin-the-thao/{cateSlug}", viewModel.CateInfo.Url);
        }

        [Fact]
        public async Task Single_PostInSubCategory_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            var param = fixture.Create<GetRecentPostsParam>();
            Post expected = fixture.Create<Post>();
            expected.CatesName = "schedule,transfer";
            expected.CategoriesSlug = $"{ Constants.MatchScheduleSlug }, { Constants.TransferSlug}";
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new CategoryGeneral { Id = 1, Slug = "tin-the-thao" },
                new CategoryGeneral { Id = 2, Slug = Constants.MatchScheduleSlug }
            };
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";

            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.PostDetailWC(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(Constants.MatchScheduleSlug, viewModel.CateInfo.Slug);
        }

        [Fact]
        public async Task Single_PostInGeneralCategory_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            var param = fixture.Create<GetRecentPostsParam>();
            Post expected = fixture.Create<Post>();
            expected.CatesName = "bong-da";
            expected.CategoriesSlug = "bong-da";
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new CategoryGeneral { Id = 1, Slug = "tin-the-thao" },
                new CategoryGeneral { Id = 1, Slug = "bong-da" }
            };
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expected.CatesName = $"{ Constants.Football }, { Constants.Video}";
            expected.CategoriesSlug = $"{ Constants.FootballSlug }, { Constants.VideoSlug}";
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.PostDetailWC(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(Constants.FootballSlug, viewModel.CateInfo.Slug);
            Assert.Equal("/tin-the-thao/bong-da", viewModel.CateInfo.Url);
        }

        [Fact]
        public async Task Single_PostInEventCategory_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            var param = fixture.Create<GetRecentPostsParam>();
            Post expected = fixture.Create<Post>();
            expected.CatesName = WorldCup2022.Homepage;
            expected.CategoriesSlug = WorldCup2022.HomepageSlug;
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new CategoryGeneral { Id = 1, Slug = "tin-the-thao" },
                new CategoryGeneral { Id = 1, Slug = "bong-da" }
            };
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.PostDetailWC(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(WorldCup2022.HomepageSlug, viewModel.CateInfo.Slug);
            Assert.Equal($"/{WorldCup2022.HomepageSlug}", viewModel.CateInfo.Url);
        }

        #region Helpers
        private WorldCup2022Controller GetController()
        {
            dataControlService = Substitute.For<IDataControlService>();
            postService = Substitute.For<IPostService>();
            matchService = Substitute.For<IMatchService>();
            leagueService = Substitute.For<ILeagueService>();
            configuration = Substitute.For<IConfiguration>();
            sessionService = Substitute.For<ISessionService>();
            userService = Substitute.For<IUserService>();
            var controller = new WorldCup2022Controller(postService, dataControlService, matchService, leagueService, configuration, sessionService, userService);
            return controller;
        }
        #endregion Helpers
    }
}
