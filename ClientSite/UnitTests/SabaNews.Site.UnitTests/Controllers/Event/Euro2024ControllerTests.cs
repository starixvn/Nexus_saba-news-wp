namespace SabaNews.Site.UnitTests.Controllers.Event
{
    using System.Threading.Tasks;
    using System.Collections.Generic;
    using System.Linq;
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Core.Constants;
    using SabaNews.Services.Interfaces;
    using SabaNews.Services;
    using Xunit;
    using SabaNews.Site.Controllers.Event;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Services;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Models.ViewModel.Event.Euro2024;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Categories;
    using SabaNews.Core.Models;
    using Microsoft.AspNetCore.Http;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.ViewModel.Poll;
    using SabaNews.Models.Options;
    using System.Net;

    public class Euro2024ControllerTests : Controller
    {
        private IDataControlService dataControlService;
        private IPostService postService;
        private IConfiguration configuration;
        private ISessionService sessionService;
        private IUserService userService;
        private ILeagueService leagueService;
        private IOptionService optionService;
        private IPollService pollService;
        private IMatchService matchService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task Index_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var expected = fixture.Create<Euro2024ViewModel>();
            dataControlService.GetEuro2024PageViewModel().Returns(expected);
            //Act
            var actual = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(actual);
            var viewModel = Assert.IsType<Euro2024ViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel);
            Assert.Equal("~/Views/Event/Euro2024/Index.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task LastestNews_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var news = fixture.Create<IEnumerable<Post>>();
            var cateSlugCriteria = fixture.Create<GetPostsByCateSlugCriteria>(); 
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.LastestNews();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/LastestNews.cshtml", viewResult.ViewName);
            var newsResult = Assert.IsType<LastestNewsViewModel>(viewResult.Model);
            Assert.Equal(news, newsResult.Posts);

        }

        [Fact]
        public async Task CommentaryNews_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var news = fixture.Create<IEnumerable<Post>>();
            var cateSlugCriteria = fixture.Create<GetPostsByCateSlugCriteria>(); 
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.CommentaryNews();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/LastestNews.cshtml", viewResult.ViewName);
            var newsResult = Assert.IsType<LastestNewsViewModel>(viewResult.Model);
            Assert.Equal(news, newsResult.Posts);

        }
        [Fact]
        public async Task Video_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var news = fixture.Create<IEnumerable<Post>>();
            var cateSlugCriteria = fixture.Create<GetPostsByCateSlugCriteria>(); 
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.Video();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/Video.cshtml", viewResult.ViewName);
            var newsResult = Assert.IsType<VideoViewModel>(viewResult.Model);
            Assert.Equal(news, newsResult.Videos);

        }

        [Fact]
        public async Task Standing_WhenCurrentStageIsGroup_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var leagueID = Euro2024.LeagueId;
            var league =fixture.Create<League>();
            league.CurrentStageId = Euro2024.GroupStageId;
            var news = fixture.Create<IList<SeasonStandings>>();
            var cateSlugCriteria = fixture.Create<GetLeagueSeasonStandingCriteria>();
            leagueService.GetLeagueSeasonStanding(Arg.Do<GetLeagueSeasonStandingCriteria>(x => cateSlugCriteria = x)).Returns(news);
            leagueService.GetLeagueByIdAsync(Arg.Do<long>(x => leagueID = x)).Returns(league);
            //Act
            var result = await controller.Standing();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/DetailStanding.cshtml", viewResult.ViewName);
            var model = Assert.IsType<StandingDetailViewModel>(viewResult.Model);
            Assert.False(model.IsKnockoutStage);
        }
        

        [Fact]
        public async Task Artile_WhenSlugNotContainHtml_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = "bai-viet";
            var postType = fixture.Create<string>();
            Post expected = null;

            postService.GetPostDetailSummaryAsync(Arg.Do<string>(x => x = slug)).Returns(expected);

            //Act
            var result = await controller.Article(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Artile_WhenSlugContainHtmlAndPostIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = "bai-viet.html";
            var postType = fixture.Create<string>();
            Post expected = null;

            postService.GetPostDetailSummaryAsync(Arg.Do<string>(x => x = slug)).Returns(expected);

            //Act
            var result = await controller.Article(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }

        [Fact]
        public async Task Artile_WhenPostSlugIsNotSame_RedirectExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = "bai-viet-123.html";
            var postId = fixture.Create<long>();
            var postType = fixture.Create<string>();
            Post expected = fixture.Create<Post>();
            expected.Slug = "test";

            postService.GetPostDetailAsync(Arg.Do<long>(x => x = postId)).Returns(expected);

            //Act
            var result = await controller.Article(slug);

            // Assert
            var redirectResult = Assert.IsType<RedirectToActionResult>(result);
            Assert.Equal("Euro2024", redirectResult.ControllerName);
            Assert.Equal("Article", redirectResult.ActionName);
        }


        [Fact]
        public async Task Article_WhenPostIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = "bai-viet-123.html";
            var postId = fixture.Create<long>();
            Post expected = null;

            postService.GetPostDetailAsync(Arg.Do<long>(x => x = postId)).Returns(expected);

            //Act
            var result = await controller.Article(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal(Constants.EmptyBlogView, viewResult.ViewName);
        }
        

        [Fact]
        public async Task Single_WhenAuthencated_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = "bai-viet-123.html";
            var postId = fixture.Create<long>();
            var param = fixture.Create<GetRecentPostsParam>();
            var expected = fixture.Create<Post>();
            expected.Slug = "bai-viet";
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new() { Id = 1, Slug = "nhan-dinh-euro-2024" }
            };
            var expectedUser = fixture.Create<UserSession>();
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            sessionService.User = expectedUser;
            postService.GetPostDetailAsync(Arg.Do<long>(x => x = postId)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = 1)).Returns(expectedUser);
            //Act
            var result = await controller.Article(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal("~/Views/Event/Euro2024/Article.cshtml", viewResult.ViewName);
            Assert.False(viewModel.IsVideo);
        }

        [Fact]
        public async Task Single_WhenIsVideoPost_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var slug = "bai-viet-123.html";
            var postId = fixture.Create<long>();
            var param = fixture.Create<GetRecentPostsParam>();
            Post expected = fixture.Create<Post>();
            expected.Slug = "bai-viet";
            var expectedCateInfos = new List<CategoryGeneral>()
            {
                new() { Id = 1, Slug = "video" },
                new() { Id = 2, Slug = "video-euro-2024" }
            };
            sessionService.User = null;
            expected.Type = Constants.VideoPostType;
            postService.GetPostDetailAsync(Arg.Do<long>(x => x = postId)).Returns(expected);
            postService.GetCateParentsAsync(Arg.Do<string>(x => x = slug)).Returns(expectedCateInfos);

            //Act
            var result = await controller.Article(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal("~/Views/Event/Euro2024/Article.cshtml", viewResult.ViewName);
            Assert.True(viewModel.IsVideo);

        }
        [Fact]
        public void MatchDetail_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();

            //Act
            var result = controller.MatchDetail(10);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/MatchDetail.cshtml", viewResult.ViewName);



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
            Assert.Equal("~/Views/Event/Euro2024/Fixture.cshtml", viewResult.ViewName);

        }

        [Fact]
        public async Task GetRelatedPosts_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var postId = fixture.Create<int>();
            var slug = fixture.Create<string>();

            var expected = fixture.Create<IEnumerable<Post>>();
            var cateSlugCriteria = fixture.Create<GetPostsByCateSlugCriteria>();
            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(expected);

            //Act
            var result = await controller.GetRelatedPosts(slug, postId);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/_RelatedPosts.cshtml", viewResult.ViewName);
            var model = Assert.IsAssignableFrom<IEnumerable<Post>>(viewResult.Model);
            Assert.Equal(expected, model);
        }

        [Fact]
        public async Task GetHomepageStandings_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var standings = fixture.Create<IList<SeasonStandings>>();
            var standingsCriteria = fixture.Create<GetLeagueSeasonStandingCriteria>();
            leagueService.GetLeagueSeasonStanding(Arg.Do<GetLeagueSeasonStandingCriteria>(x => standingsCriteria = x)).Returns(standings);
            var groupsStageStanding = standings.Where(standing => standing.StageId == Euro2024.GroupStageId).ToList();
            var standingGroup = controller.GroupStandingsByGroup(groupsStageStanding);
            //Act
            var result = await controller.GetHomepageStandings();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/_HomepageStanding.cshtml", viewResult.ViewName);
            var standingResult = Assert.IsType<List<StandingViewModel>>(viewResult.Model);
            Assert.Equal(standingGroup.Count, standingResult.Count);
        }

        [Fact]
        public async Task GetNewsGrid_WhenGridIsVideo_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<CategoryPostParams>();
            param.IsVideo = true;
            var news = fixture.Create<IEnumerable<Post>>();
            var cateSlugCriteria = fixture.Create<GetPostsByCateSlugCriteria>();

            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.GetNewsGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/_VideoGridView.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task GetNewsGrid_WhenGridIsNotVideo_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<CategoryPostParams>();
            param.IsVideo = false;
            var news = fixture.Create<IEnumerable<Post>>();
            var cateSlugCriteria = fixture.Create<GetPostsByCateSlugCriteria>();

            postService.GetPostsByCateSlugAsync(Arg.Do<GetPostsByCateSlugCriteria>(x => cateSlugCriteria = x)).Returns(news);

            //Act
            var result = await controller.GetNewsGrid(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/_NewsGridView.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task GetFixture_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
           
            var leagueId = fixture.Create<long>();
            var seasonId = fixture.Create<long>();
            var expected = fixture.Create<MatchLeague>();
            var expectedJson = Json(new { expected.League, expected.Matches });

            dataControlService.GetMatchBySeasonAsync(Arg.Do<long>(x => leagueId = x), Arg.Do<long>(x => seasonId = x)).Returns(expected);

            //Act
            var result = await controller.GetFixture();

            // Assert
            var viewResult = Assert.IsType<JsonResult>(result);
            var actualLeague = Assert.IsAssignableFrom<League>(viewResult.Value.GetType().GetProperty("League").GetValue(viewResult.Value));
            var actualMatches = Assert.IsAssignableFrom<IList<Match>>(viewResult.Value.GetType().GetProperty("Matches").GetValue(viewResult.Value));
            Assert.Equal(expected.League, actualLeague);
            Assert.Equal(expected.Matches, actualMatches);
        }

        [Fact]
        public async Task GetTopScorers_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var topScorers = fixture.Create<IList<TopScorers>>();
            var topCriteria = fixture.Create<GetTop10ScorersCriteria>();
            leagueService.GetTop10ScorersAsync(Arg.Do<GetTop10ScorersCriteria>(x => topCriteria = x)).Returns(topScorers);

            //Act
            var result = await controller.GetTopScorers();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/_TopScorers.cshtml", viewResult.ViewName);
            var topResult = Assert.IsAssignableFrom<IList<TopScorers>>(viewResult.Model);
            Assert.Equal(topScorers, topResult);
        }

        [Fact]
        public async Task GetPollData_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Headers["x-forwarded-for"] = fixture.Create<string>();

            var optionKey = fixture.Create<string>();
            var questId = fixture.Create<int>();
            var themeId = fixture.Create<int>();
            var criteria = fixture.Create<GetPollDetailCriteria>();

            var expected = fixture.Create<PollViewModel>();
            var expectedOption = new Option { OptionValue = "4" };
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
           
            optionService.GetOptionAsync(Arg.Do<string>(x => optionKey = x)).Returns(expectedOption);
            pollService.GetPollDetailAsync(Arg.Do<GetPollDetailCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var result = await controller.GetPollData();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsType<PollViewModel>(viewResult.Model);
            Assert.Equal("~/Views/Event/Euro2024/_PollEuro2024.cshtml", viewResult.ViewName);
            Assert.Equal(int.Parse(expectedOption.OptionValue), criteria.QuestId);
        }

        [Fact]
        public async Task VotePoll_WhenVoteSuccessful_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Headers["x-forwarded-for"] = "";
            controller.ControllerContext.HttpContext.Connection.RemoteIpAddress = fixture.Create<IPAddress>();
            var questId = fixture.Create<int>();
            var answerId = fixture.Create<int>();
            var themeId = fixture.Create<int>();
            var clientIp = fixture.Create<string>();
            var criteria = fixture.Create<GetPollDetailCriteria>();
            var expected = new CommandResult { Code = 0 };
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();

            var criteriaPoll = fixture.Create<GetPollDetailCriteria>();
            var expectedPoll = fixture.Create<PollViewModel>();
           
            pollService.GetPollDetailAsync(Arg.Do<GetPollDetailCriteria>(x => criteria = x)).Returns(expectedPoll);

            pollService.VotePollAsync(Arg.Do<int>(x => questId = x), Arg.Do<int>(x => answerId = x), Arg.Do<string>(x => clientIp = x)).Returns(expected);

            //Act
            var result = await controller.VotePoll(questId, answerId, themeId);

            // Assert
            Assert.True(expected.IsSuccessful);
        }

        [Fact]
        public async Task GetStandings_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var standings = new Fixture().Create<IList<SeasonStandings>>();
            var standingsCriteria = new Fixture().Create<GetLeagueSeasonStandingCriteria>();
            leagueService.GetLeagueSeasonStanding(Arg.Do<GetLeagueSeasonStandingCriteria>(x => standingsCriteria = x)).Returns(standings);
            var groupsStageStanding = standings.Where(standing => standing.StageId == Euro2024.GroupStageId).ToList();
            var standingGroup = controller.GroupStandingsByGroup(groupsStageStanding);
            
            //Act
            var result = await controller.GetStandings();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Views/Event/Euro2024/_Standing.cshtml", viewResult.ViewName);
            var standingResult = Assert.IsType<List<StandingViewModel>>(viewResult.Model);
            Assert.Equal(standingGroup.Count, standingResult.Count);
        }



        #region Helpers
        private Euro2024Controller GetController()
        {
            dataControlService = Substitute.For<IDataControlService>();
            postService = Substitute.For<IPostService>();
            configuration = Substitute.For<IConfiguration>();
            sessionService = Substitute.For<ISessionService>();
            userService = Substitute.For<IUserService>();
            leagueService = Substitute.For<ILeagueService>();
            optionService = Substitute.For<IOptionService>();
            pollService = Substitute.For<IPollService>();
            matchService = Substitute.For<IMatchService>();
            var controller = new Euro2024Controller(dataControlService, postService, sessionService, configuration, userService, pollService, leagueService, optionService, matchService);

            return controller;
        }

        #endregion Helpers
    }
}
