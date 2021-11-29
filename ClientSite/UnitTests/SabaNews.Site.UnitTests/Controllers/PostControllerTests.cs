namespace SabaNews.Site.UnitTests.Controllers
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Models.Posts;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Controllers;
    using System.Threading.Tasks;
    using Xunit;
    using SabaNews.Core.Constants;
    using System.Collections.Generic;
    using SabaNews.Core.Models;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.ViewModel.Poll;
    using Microsoft.AspNetCore.Http;
    using System.Net;
    using System;

    public class PostControllerTests : Controller
    {
        private IPostService postService;
        private IConfiguration configuration;
        private ISessionService sessionService;
        private IUserService userService;
        private IStatisticService statisticService;
        private IPollService pollService;
        private readonly IFixture fixture = new Fixture();

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
            var result = await controller.Single(slug);

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
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expectedUser.SavedPosts = null;
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => x = param)).Returns(expectedRecentPosts);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.Single(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(expectedRecentPosts, viewModel.RecentPosts);
            Assert.Null(viewModel.ViewData);
            Assert.Null(viewModel.RelatedPosts);
            await postService.Received().UpdateViewAsync(expected.ID, false);
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
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expected.Type = Constants.VideoPostType;
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => x = param)).Returns(expectedRecentPosts);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.Single(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(expectedRecentPosts, viewModel.RecentPosts);
            await postService.Received().UpdateViewAsync(expected.ID, true);
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
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expected.Type = Constants.VideoPostType;
            expected.CategoriesSlug = cateSlug;
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => x = param)).Returns(expectedRecentPosts);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.Single(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(expectedRecentPosts, viewModel.RecentPosts);
            Assert.Equal(Constants.OtherPostSlug, viewModel.CateSlug);
            await postService.Received().UpdateViewAsync(expected.ID, true);
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
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expected.CatesName = "schedule,transfer";
            expected.CategoriesSlug = $"{ Constants.MatchScheduleSlug }, { Constants.TransferSlug}";
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => x = param)).Returns(expectedRecentPosts);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.Single(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(expectedRecentPosts, viewModel.RecentPosts);
            Assert.Equal(Constants.MatchScheduleSlug, viewModel.CateSlug);
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
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
            var expectedUser = fixture.Create<UserSession>();
            sessionService.User = fixture.Create<UserSession>();
            expected.CatesName = $"{ Constants.Football }, { Constants.Video}";
            expected.CategoriesSlug = $"{ Constants.FootballSlug }, { Constants.VideoSlug}";
            expectedUser.SavedPosts = "[{\"PostID\":3963,\"Date\":\"2021-10-21\"},{\"PostID\":4255,\"Date\":\"2021-10-21\"},{\"PostID\":4249,\"Date\":\"2021-10-21\"}]";
            postService.GetPostDetailBySlugAsync(Arg.Do<string>(x => x = slug), Arg.Do<string>(x => x = postType)).Returns(expected);
            postService.GetRecentPostsAsync(Arg.Do<GetRecentPostsParam>(x => x = param)).Returns(expectedRecentPosts);
            userService.GetUserByIdAsync(Arg.Do<int>(x => x = sessionService.User.Id)).Returns(expectedUser);

            //Act
            var result = await controller.Single(slug);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<SingleViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.SinglePost);
            Assert.Equal(expectedRecentPosts, viewModel.RecentPosts);
            Assert.Equal(Constants.FootballSlug, viewModel.CateSlug);
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
            var questId = fixture.Create<int>();
            var themeId = fixture.Create<int>();
            var criteria = fixture.Create<GetPollCriteria>();
            var expected = fixture.Create<PollViewModel>();
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();
           
            pollService.GetPollAsync(Arg.Do<GetPollCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var result = await controller.GetPollData(questId, themeId);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsType<PollViewModel>(viewResult.Model);
            Assert.Equal("_Poll", viewResult.ViewName);
            Assert.Equal(questId, criteria.QuestId);
            Assert.Equal(themeId, criteria.ThemeId);
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
            var criteria = fixture.Create<GetPollCriteria>();
            var expected = new CommandResult { Code = 0 };
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();

            pollService.VotePollAsync(Arg.Do<int>(x => questId = x), Arg.Do<int>(x => answerId = x), Arg.Do<string>(x => clientIp = x)).Returns(expected);

            //Act
            var result = await controller.VotePoll(questId, answerId, themeId);

            // Assert
            Assert.True(expected.IsSuccessful);
        }

        [Fact]
        public async Task VotePoll_WhenVoteFailed_ReturnExpectedData()
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
            var criteria = fixture.Create<GetPollCriteria>();
            var expected = new CommandResult { Code = 1 };
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();

            pollService.VotePollAsync(Arg.Do<int>(x => questId = x), Arg.Do<int>(x => answerId = x), Arg.Do<string>(x => clientIp = x)).Returns(expected);

            //Act
            var result = await controller.VotePoll(questId, answerId, themeId);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var template = Assert.IsAssignableFrom<CommandResult>(jsonResult.Value);
            Assert.Equal(expected, template);
        }

        [Fact]
        public async Task ViewVideo_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var postId = fixture.Create<int>();
            var criteria = fixture.Create<GetPollCriteria>();
            var expected = fixture.Create<CommandResult>();
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();

            postService.UpdateVideoView(Arg.Do<int>(x => postId = x)).Returns(expected);

            //Act
            var result = await controller.ViewVideo(postId);

            // Assert
            var okResult = Assert.IsType<OkObjectResult>(result);
            Assert.Equal(expected, okResult.Value);
        }

        [Fact]
        public async Task GetPostViewStatistic_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var fromDate = fixture.Create<DateTime>();
            var toDate = fixture.Create<DateTime>();
            var criteria = fixture.Create<GetPollCriteria>();
            var expected = fixture.Create<IEnumerable<PostStatisticInfo>>();
            var expectedRecentPosts = fixture.Create<IEnumerable<Post>>();

            statisticService.GetPostViewStatistic(Arg.Do<DateTime>(x => fromDate = x), Arg.Do<DateTime>(x => fromDate = x)).Returns(expected);

            //Act
            var result = await controller.GetPostViewStatistic(fromDate, toDate);

            // Assert
            var okResult = Assert.IsType<OkObjectResult>(result);
            Assert.Equal(expected, okResult.Value);
        }

        #region Helpers
        private PostController GetController()
        {
            pollService = Substitute.For<IPollService>();
            postService = Substitute.For<IPostService>();
            configuration = Substitute.For<IConfiguration>();
            userService = Substitute.For<IUserService>();
            sessionService = Substitute.For<ISessionService>();
            statisticService = Substitute.For<IStatisticService>();
            var controller = new PostController(postService, configuration, sessionService, userService, statisticService, pollService);

            return controller;
        }
        #endregion Helpers
    }
}
