namespace SabaNews.Site.UnitTests.Controllers.Livescore
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Caching;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Options;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using SabaNews.Site.Controllers.Livescore;
    using Xunit;

    public class SoccerControllerTests
    {
        private IPostService postService;
        private ILeagueService leagueService;
        private IMatchService matchService;
        private ICacheWrapperService cacheService;
        private readonly IFixture fixture = new Fixture();

        #region Index

        [Fact]
        public async Task Index_DescriptionIsNullOrWhiteSpace_ReturnExpectedViewResult()
        {
            // Arrange
            var pageDetail = new Category
            {
                Title = "title"
            };
            var controller = GetController();
            postService.GetPageDetailBySlugAsync("lich-thi-dau").Returns(pageDetail);

            // Act
            var result = await controller.Index(null);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);

            Assert.Single(viewResult.ViewData);
            Assert.Null(viewResult.ViewData["EventDate"]);
            Assert.Equal("~/Views/Livescore/Soccer/Index.cshtml", viewResult.ViewName);
            Assert.Null(viewModel.Post);
            Assert.Equal(pageDetail, viewModel.Category);
            Assert.Equal(pageDetail.CatId, viewModel.Header.CategoryId);
            Assert.Equal(pageDetail.Title, viewModel.Header.Title);
            Assert.Equal(pageDetail.Title, viewModel.Header.Description);
            Assert.Null(viewModel.Header.HeadTitle);
            Assert.Null(viewModel.Header.Controller);
            Assert.Null(viewModel.Header.Slug);
            Assert.False(viewModel.Header.IsChildren);
            Assert.Null(viewModel.Header.ActiveCategory);
            Assert.Null(viewModel.Header.Keyword);
        }

        [Fact]
        public async Task Index_DescriptionIsValid_ReturnExpectedViewResult()
        {
            // Arrange
            var pageDetail = new Category
            {
                Title = "title",
                Description = "description"
            };
            var controller = GetController();
            postService.GetPageDetailBySlugAsync("lich-thi-dau").Returns(pageDetail);

            // Act
            var result = await controller.Index(null);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);

            Assert.Single(viewResult.ViewData);
            Assert.Null(viewResult.ViewData["EventDate"]);
            Assert.Equal("~/Views/Livescore/Soccer/Index.cshtml", viewResult.ViewName);
            Assert.Null(viewModel.Post);
            Assert.Equal(pageDetail, viewModel.Category);
            Assert.Equal(pageDetail.CatId, viewModel.Header.CategoryId);
            Assert.Equal(pageDetail.Title, viewModel.Header.Title);
            Assert.Equal(pageDetail.Description, viewModel.Header.Description);
            Assert.Null(viewModel.Header.HeadTitle);
            Assert.Null(viewModel.Header.Controller);
            Assert.Null(viewModel.Header.Slug);
            Assert.False(viewModel.Header.IsChildren);
            Assert.Null(viewModel.Header.ActiveCategory);
            Assert.Null(viewModel.Header.Keyword);
        }

        [Fact]
        public async Task Index_EventDateIsValid_ReturnExpectedViewResult()
        {
            // Arrange
            var eventDate = fixture.Create<DateTime>();
            var pageDetail = new Category
            {
                Title = "title",
                Description = "description"
            };
            var controller = GetController();
            postService.GetPageDetailBySlugAsync("lich-thi-dau").Returns(pageDetail);

            // Act
            var result = await controller.Index(eventDate);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);

            Assert.Single(viewResult.ViewData);
            Assert.Equal(eventDate, viewResult.ViewData["EventDate"]);
        }

        #endregion

        #region Live

        [Fact]
        public async Task Live_DescriptionIsNullOrWhiteSpace_ReturnExpectedViewResult()
        {
            // Arrange
            var pageDetail = new Category
            {
                Title = "title"
            };
            var controller = GetController();
            postService.GetPageDetailBySlugAsync("lich-thi-dau").Returns(pageDetail);

            // Act
            var result = await controller.Live();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);

            Assert.Empty(viewResult.ViewData);
            Assert.Equal("~/Views/Livescore/Soccer/Live.cshtml", viewResult.ViewName);
            Assert.Null(viewModel.Post);
            Assert.Equal(pageDetail, viewModel.Category);
            Assert.Equal(pageDetail.CatId, viewModel.Header.CategoryId);
            Assert.Equal(pageDetail.Title, viewModel.Header.Title);
            Assert.Equal(pageDetail.Title, viewModel.Header.Description);
            Assert.Null(viewModel.Header.HeadTitle);
            Assert.Null(viewModel.Header.Controller);
            Assert.Null(viewModel.Header.Slug);
            Assert.False(viewModel.Header.IsChildren);
            Assert.Null(viewModel.Header.ActiveCategory);
            Assert.Null(viewModel.Header.Keyword);
        }

        [Fact]
        public async Task Live_DescriptionIsValid_ReturnExpectedViewResult()
        {
            // Arrange
            var pageDetail = new Category
            {
                Title = "title",
                Description = "description"
            };
            var controller = GetController();
            postService.GetPageDetailBySlugAsync("lich-thi-dau").Returns(pageDetail);

            // Act
            var result = await controller.Live();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<PageViewModel>(viewResult.Model);

            Assert.Empty(viewResult.ViewData);
            Assert.Equal("~/Views/Livescore/Soccer/Live.cshtml", viewResult.ViewName);
            Assert.Null(viewModel.Post);
            Assert.Equal(pageDetail, viewModel.Category);
            Assert.Equal(pageDetail.CatId, viewModel.Header.CategoryId);
            Assert.Equal(pageDetail.Title, viewModel.Header.Title);
            Assert.Equal(pageDetail.Description, viewModel.Header.Description);
            Assert.Null(viewModel.Header.HeadTitle);
            Assert.Null(viewModel.Header.Controller);
            Assert.Null(viewModel.Header.Slug);
            Assert.False(viewModel.Header.IsChildren);
            Assert.Null(viewModel.Header.ActiveCategory);
            Assert.Null(viewModel.Header.Keyword);
        }

        #endregion

        [Fact]
        public void MatchResult_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var league = fixture.Create<string>();
            var match = fixture.Create<string>();
            var matchId = fixture.Create<long>();
            var controller = GetController();

            // Act
            var result = controller.MatchResult(league, match, matchId);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<long>(viewResult.Model);

            Assert.Empty(viewResult.ViewData);
            Assert.Equal("~/Views/Livescore/Soccer/Detail.cshtml", viewResult.ViewName);
            Assert.Equal(matchId, viewModel);
        }

        [Fact]
        public void LeagueDetail_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var league = fixture.Create<string>();
            var seasonId = fixture.Create<long>();
            var date = fixture.Create<DateTime>();
            var leagueId = fixture.Create<long>();
            var selectedTab = fixture.Create<string>();
            var controller = GetController();

            // Act
            var result = controller.LeagueDetail(league, seasonId, date, leagueId, selectedTab);

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<GetMatchBySeasonParams>(viewResult.Model);
            Assert.Empty(viewResult.ViewData);
            Assert.Equal("~/Views/Livescore/Soccer/LeagueDetail.cshtml", viewResult.ViewName);
            Assert.Equal(leagueId, viewModel.LeagueId);
            Assert.Equal(seasonId, viewModel.SeasonId);
            Assert.Equal(date, viewModel.Date);
            Assert.Equal(selectedTab, viewModel.SelectedTab);
            Assert.Equal(0, viewModel.Timezone);
        }

        [Fact]
        public void FavouriteMatch_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();

            // Act
            var result = controller.FavouriteMatch();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            Assert.Empty(viewResult.ViewData);
            Assert.Equal("~/Views/Livescore/Soccer/FavouriteMatch.cshtml", viewResult.ViewName);
            Assert.Null(viewResult.Model);
        }

        #region GetMatches

        [Fact]
        public async Task GetMatches_WhenCacheIsDisabled_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var eventDate = new DateTime(2022, 5, 25);
            var fromTime = eventDate.AddHours(-7);
            var toTime = eventDate.AddDays(1).AddSeconds(-1).AddHours(-7);
            var criteria = new GetMatchByDateCriteria
            {
                EventDate = eventDate,
                Timezone = 7
            };
            var controller = GetController(false);
            matchService.GetMatchByDateAsync(criteria).Returns(matches);

            // Act
            var result = await controller.GetMatches(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);

            Assert.Equal(eventDate, criteria.EventDate);
            Assert.Equal(7, criteria.Timezone);
            Assert.Equal(fromTime, criteria.FromTime);
            Assert.Equal(toTime, criteria.ToTime);
            Assert.Equal(1, criteria.SportId);
        }

        [Fact]
        public async Task GetMatches_WhenCacheReturnEmptyAndInRangeTimeout_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            List<Match> tmpMatches = null;
            var key = fixture.Create<string>();
            var today = DateTime.UtcNow.Date;
            var eventDate = today.AddDays(1);
            var fromTime = eventDate.AddHours(-7);
            var toTime = eventDate.AddDays(1).AddSeconds(-1).AddHours(-7);
            var criteria = new GetMatchByDateCriteria
            {
                EventDate = eventDate,
                Timezone = 7
            };
            var controller = GetController();
            cacheService.GetAsync<IList<Match>>(Arg.Do<string>(x => x = key)).Returns(tmpMatches);
            matchService.GetMatchByDateAsync(criteria).Returns(matches);

            // Act
            var result = await controller.GetMatches(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);

            Assert.Equal(eventDate, criteria.EventDate);
            Assert.Equal(7, criteria.Timezone);
            Assert.Equal(fromTime, criteria.FromTime);
            Assert.Equal(toTime, criteria.ToTime);
            Assert.Equal(1, criteria.SportId);
            await cacheService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IList<Match>>(), Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task GetMatches_WhenCacheReturnEmptyAndDateMoreThanTwo_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            List<Match> tmpMatches = null;
            var key = fixture.Create<string>();
            var today = DateTime.UtcNow.Date;
            var eventDate = today.AddDays(3);
            var fromTime = eventDate.AddHours(-7);
            var toTime = eventDate.AddDays(1).AddSeconds(-1).AddHours(-7);
            var criteria = new GetMatchByDateCriteria
            {
                EventDate = eventDate,
                Timezone = 7
            };
            var controller = GetController();
            cacheService.GetAsync<IList<Match>>(Arg.Do<string>(x => x = key)).Returns(tmpMatches);
            matchService.GetMatchByDateAsync(criteria).Returns(matches);

            // Act
            var result = await controller.GetMatches(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);

            Assert.Equal(eventDate, criteria.EventDate);
            Assert.Equal(7, criteria.Timezone);
            Assert.Equal(fromTime, criteria.FromTime);
            Assert.Equal(toTime, criteria.ToTime);
            Assert.Equal(1, criteria.SportId);
            await cacheService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IList<Match>>(), Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task GetMatches_WhenCacheReturnEmptyAndDateLowerThanTwo_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            List<Match> tmpMatches = null;
            var key = fixture.Create<string>();
            var today = DateTime.UtcNow.Date;
            var eventDate = today.AddDays(-3);
            var fromTime = eventDate.AddHours(-7);
            var toTime = eventDate.AddDays(1).AddSeconds(-1).AddHours(-7);
            var criteria = new GetMatchByDateCriteria
            {
                EventDate = eventDate,
                Timezone = 7
            };
            var controller = GetController();
            cacheService.GetAsync<IList<Match>>(Arg.Do<string>(x => x = key)).Returns(tmpMatches);
            matchService.GetMatchByDateAsync(criteria).Returns(matches);

            // Act
            var result = await controller.GetMatches(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);

            Assert.Equal(eventDate, criteria.EventDate);
            Assert.Equal(7, criteria.Timezone);
            Assert.Equal(fromTime, criteria.FromTime);
            Assert.Equal(toTime, criteria.ToTime);
            Assert.Equal(1, criteria.SportId);
            await cacheService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IList<Match>>(), Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task GetMatches_WhenCacheReturnExpectedData_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var key = fixture.Create<string>();
            var today = DateTime.UtcNow.Date;
            var eventDate = today.AddDays(3);
            var fromTime = eventDate.AddHours(-7);
            var toTime = eventDate.AddDays(1).AddSeconds(-1).AddHours(-7);
            var criteria = new GetMatchByDateCriteria
            {
                EventDate = eventDate,
                Timezone = 7
            };
            var controller = GetController();
            cacheService.GetAsync<IList<Match>>(Arg.Do<string>(x => x = key)).Returns(matches);
            matchService.GetMatchByDateAsync(criteria).Returns(matches);

            // Act
            var result = await controller.GetMatches(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);

            Assert.Equal(eventDate, criteria.EventDate);
            Assert.Equal(7, criteria.Timezone);
            Assert.Equal(fromTime, criteria.FromTime);
            Assert.Equal(toTime, criteria.ToTime);
            Assert.Equal(1, criteria.SportId);
        }

        #endregion

        #region GetMatchesBySeason

        [Fact]
        public async Task GetMatchesBySeason_SeasonIdEquals0_ReturnExpectedJsonResult()
        {
            // Arrange
            var date = new DateTime(2022, 5, 25);
            var fromTime = date.AddMonths(-6).AddHours(-7);
            var toTime = date.AddDays(1).AddSeconds(-1).AddMonths(6).AddHours(-7);
            var league = fixture.Create<League>();
            var matches = fixture.Create<IList<Match>>();
            var model = new GetMatchBySeasonParams
            {
                LeagueId = 1,
                Date = date,
                Timezone = 7
            };
            var criteria = new GetMatchBySeasonCriteria();
            var controller = GetController();
            matchService.GetMatchBySeasonAsync(Arg.Do<GetMatchBySeasonCriteria>(x => criteria = x)).Returns((league, matches));

            // Act
            var result = await controller.GetMatchesBySeason(model);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actualLeague = Assert.IsAssignableFrom<League>(jsonResult.Value.GetType().GetProperty("league").GetValue(jsonResult.Value));
            var actualMatches = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value.GetType().GetProperty("matches").GetValue(jsonResult.Value));
            Assert.Equal(league, actualLeague);
            Assert.Equal(matches, actualMatches);

            Assert.Equal(1, criteria.LeagueId);
            Assert.Equal(0, criteria.SeasonId);
            Assert.Equal(fromTime, criteria.FromTime);
            Assert.Equal(toTime, criteria.ToTime);
        }

        [Theory]
        [InlineData(-1)]
        [InlineData(1)]
        public async Task GetMatchesBySeason_SeasonIdNotEquals0_ReturnExpectedJsonResult(long seasonId)
        {
            // Arrange
            var date = new DateTime(2022, 5, 25);
            var league = fixture.Create<League>();
            var matches = fixture.Create<IList<Match>>();
            var model = new GetMatchBySeasonParams
            {
                LeagueId = 1,
                SeasonId = seasonId,
                Date = date,
                Timezone = 7
            };
            var criteria = new GetMatchBySeasonCriteria();
            var controller = GetController();
            matchService.GetMatchBySeasonAsync(Arg.Do<GetMatchBySeasonCriteria>(x => criteria = x)).Returns((league, matches));

            // Act
            var result = await controller.GetMatchesBySeason(model);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actualLeague = Assert.IsAssignableFrom<League>(jsonResult.Value.GetType().GetProperty("league").GetValue(jsonResult.Value));
            var actualMatches = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value.GetType().GetProperty("matches").GetValue(jsonResult.Value));
            Assert.Equal(league, actualLeague);
            Assert.Equal(matches, actualMatches);

            Assert.Equal(1, criteria.LeagueId);
            Assert.Equal(seasonId, criteria.SeasonId);
            Assert.Null(criteria.FromTime);
            Assert.Null(criteria.ToTime);
        }

        #endregion

        #region GetLiveMatches
        [Fact]
        public async Task GetLiveMatches_WhenCacheIsDisabled_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var controller = GetController(false);
            matchService.GetLiveMatchAsync().Returns(matches);

            // Act
            var result = await controller.GetLiveMatches();

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);
        }

        [Fact]
        public async Task GetLiveMatches_WhenCacheReturnNull_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            List<Match> tmpMatches = null;
            var controller = GetController();
            var key = fixture.Create<string>();
            cacheService.GetAsync<IList<Match>>(Arg.Do<string>(x => x = key)).Returns(tmpMatches);
            matchService.GetLiveMatchAsync().Returns(matches);
            // Act
            var result = await controller.GetLiveMatches();

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);
            await cacheService.Received().SetAsync(Arg.Any<string>(), Arg.Any<IList<Match>>(), Arg.Any<CacheItemOptions>());
        }

        [Fact]
        public async Task GetLiveMatches_WhenCacheReturnExpectedData_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var controller = GetController();
            var key = fixture.Create<string>();
            cacheService.GetAsync<IList<Match>>(Arg.Do<string>(x => x = key)).Returns(matches);
            // Act
            var result = await controller.GetLiveMatches();

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<Match>>(jsonResult.Value);
            Assert.Equal(matches, actual);
        }

        #endregion

        [Fact]
        public async Task GetSeasonStandings_WhenCalled_ReturnExpectedJsonResult()
        {
            // Arrange
            var criteria = fixture.Create<GetSeasonStandingsCriteria>();
            var standings = fixture.Create<IList<SeasonStandings>>();
            var controller = GetController();
            matchService.GetSeasonStandingsAsync(criteria).Returns(standings);

            // Act
            var result = await controller.GetSeasonStandings(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<SeasonStandings>>(jsonResult.Value);
            Assert.Equal(standings, actual);
        }

        [Fact]
        public async Task GetMajorLeagues_WhenCalled_ReturnExpectedJsonResult()
        {
            // Arrange
            var leagues = fixture.Create<IList<League>>();
            var controller = GetController();
            leagueService.GetMajorLeagueAsync().Returns(leagues);

            // Act
            var result = await controller.GetMajorLeagues();

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<IList<League>>(jsonResult.Value);
            Assert.Equal(leagues, actual);
        }

        [Fact]
        public async Task GetMatchDetail_WhenCalled_ReturnExpectedJsonResult()
        {
            // Arrange
            var match = fixture.Create<Match>();
            var statistic = fixture.Create<Statistic>();
            var headToHead = fixture.Create<HeadToHeadDetail>();
            var lineup = fixture.Create<LineupDetail>();
            var incidents = fixture.Create<IEnumerable<MatchIncident>>();
            var criteria = fixture.Create<GetMatchByIdCriteria>();
            var getMatchByIdCriteria = new GetMatchByIdCriteria();
            var getMatchStatisticCriteria = new GetMatchStatisticCriteria();
            var getHeadToHeadCriteria = new GetHeadToHeadCriteria();
            var getMatchLineupCriteria = new GetMatchLineupCriteria();
            var getMatchIncidentCriteria = new GetMatchIncidentCriteria();
            var controller = GetController();
            matchService.GetMatchByIdAsync(Arg.Do<GetMatchByIdCriteria>(x => getMatchByIdCriteria = x)).Returns(match);
            matchService.GetMatchStatisticAsync(Arg.Do<GetMatchStatisticCriteria>(x => getMatchStatisticCriteria = x)).Returns(statistic);
            matchService.GetHeadToHeadAsync(Arg.Do<GetHeadToHeadCriteria>(x => getHeadToHeadCriteria = x)).Returns(headToHead);
            matchService.GetLineupAsync(Arg.Do<GetMatchLineupCriteria>(x => getMatchLineupCriteria = x)).Returns(lineup);
            matchService.GetMatchIncidentAsync(Arg.Do<GetMatchIncidentCriteria>(x => getMatchIncidentCriteria = x)).Returns(incidents);

            // Act
            var result = await controller.GetMatchDetail(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<MatchDetail>(jsonResult.Value);
            Assert.Equal(match, actual.GeneralInfo);
            Assert.Equal(statistic, actual.Statistic);
            Assert.Equal(headToHead, actual.HeadToHead);
            Assert.Equal(lineup, actual.Lineup);
            Assert.Equal(incidents, actual.Incidents);

            Assert.Equal(criteria.MatchId, getMatchByIdCriteria.MatchId);
            Assert.Equal(criteria.MatchId, getMatchStatisticCriteria.MatchId);
            Assert.Equal(criteria.MatchId, getHeadToHeadCriteria.MatchId);
            Assert.Equal(criteria.MatchId, getMatchLineupCriteria.MatchId);
            Assert.Equal(criteria.MatchId, getMatchIncidentCriteria.MatchId);
        }

        [Fact]
        public async Task GetMatchByIds_WhenCalled_ReturnExpectedJsonResult()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var liveMatches = fixture.Create<IList<Match>>();
            var criteria = fixture.Create<GetMatchByIdsCriteria>();
            var controller = GetController();
            matchService.GetMatchByIdsAsync(criteria).Returns(matches);
            matchService.GetLiveMatchAsync().Returns(liveMatches);

            // Act
            var result = await controller.GetMatchByIds(criteria);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            var actual = Assert.IsAssignableFrom<MatchFavourite>(jsonResult.Value);
            Assert.Equal(matches, actual.Matches);
            Assert.Equal(liveMatches, actual.LiveMatches);
        }

        #region Helpers

        private SoccerController GetController(bool enabledCache = true)
        {
            var cacheConfigs = new LivescoreMainPageCacheConfig
            {
                Enabled = enabledCache,
                DateRange = 2,
                OutOfRangeTimeout = 5
            };
            var options = Substitute.For<IOptionsSnapshot<LivescoreMainPageCacheConfig>>();
            options.Value.Returns(cacheConfigs);

            cacheService = Substitute.For<ICacheWrapperService>();
            postService = Substitute.For<IPostService>();
            leagueService = Substitute.For<ILeagueService>();
            matchService = Substitute.For<IMatchService>();
            return new SoccerController(options, cacheService, postService, leagueService, matchService);
        }

        #endregion
    }
}