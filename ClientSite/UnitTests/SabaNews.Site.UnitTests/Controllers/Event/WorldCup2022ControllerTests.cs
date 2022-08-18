namespace SabaNews.Site.UnitTests.Controllers.Event
{
    using AutoFixture;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.AspNetCore.Mvc.Routing;
    using NSubstitute;
    using SabaNews.Criteria.Posts;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Event.WorldCup;
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
            Assert.Equal("~/Views/Event/WorldCup2022/Commentary.cshtml", viewResult.ViewName);
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

        #region Helpers
        private WorldCup2022Controller GetController()
        {
            dataControlService = Substitute.For<IDataControlService>();
            postService = Substitute.For<IPostService>();
            matchService = Substitute.For<IMatchService>();
            leagueService = Substitute.For<ILeagueService>();
            var controller = new WorldCup2022Controller(postService, dataControlService, matchService, leagueService);
            return controller;
        }

        private void GetDefaultContext(WorldCup2022Controller controller)
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
