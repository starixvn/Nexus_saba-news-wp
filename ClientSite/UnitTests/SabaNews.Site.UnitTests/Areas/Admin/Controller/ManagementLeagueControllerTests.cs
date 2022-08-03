namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.ManagementLeague;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Threading.Tasks;
    using Xunit;
    public class ManagementLeagueControllerTests : AdminController
    {
        private ILeagueService leagueService;

        [Fact]
        public void ManagementLeague_WhenCalled_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();

            //Act
            var result = controller.Index();

            // Assert
            Assert.IsType<ViewResult>(result);
        }

        [Fact]
        public async Task GetManagementLeagueData_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = new Fixture().Create<ManagementLeagueFilterModel>();
            var expected = new Fixture().Create<IList<League>>();
            leagueService.GetLeaguesAsync().Returns(expected);
            leagueService.SearchLeague(expected, param.Search, param.IsMajor).Returns(expected);
            leagueService.SortLeague(expected, param.TypeSort).Returns(expected);
            //Act
            var result = await controller.GetManagementLeagueData(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("_ManagementLeagueDataGrid", viewResult.ViewName);
        }

        [Fact]
        public async Task GetAllLeagues_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var expected = new Fixture().Create<IList<League>>();
            leagueService.GetAllLeaguesAsync().Returns(expected);
            leagueService.GetLeaguesAsync().Returns(expected);

            //Act
            var result = await controller.GetAllLeagues();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("_AddLeagueDataGrid", viewResult.ViewName);
        }

        [Fact]
        public async Task ChangeMajor_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var league = new Fixture().Create<League>();
            var expected = new Fixture().Create<CommandResult>();
            leagueService.UpdateLeaguesExtraAsync(league).Returns(expected);
            //Act
            var result = await controller.UpdateLeagueExtra(league);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expected, jsonResult.Value);
        }

        [Fact]
        public async Task RemoveLeagueExtra_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var leagueId = new Fixture().Create<int>();
            var expected = new Fixture().Create<CommandResult>();
            leagueService.DeleteLeaguesExtraAsync(leagueId).Returns(expected);
            //Act
            var result = await controller.RemoveLeagueExtra(leagueId);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expected, jsonResult.Value);
        }

        [Fact]
        public async Task AddLeagueExtra_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var league = new Fixture().Create<LeagueUpdateViewModel>();

            var expected = new Fixture().Create<CommandResult>();
            leagueService.InsertLeaguesExtraAsync(league.LeagueId).Returns(expected);
            leagueService.UpdateLeaguesExtraAsync(new League { LeagueId = league.LeagueId, IsMajor = league.IsMajor, Priority = league.Priority }).Returns(expected);
            leagueService.UpdateLeaguesAsync(new UpdateLeagueCriteria { LeagueId = league.LeagueId, LeagueNameVN = league.LeagueNameVN, CountryVN = league.CountryVN }).Returns(expected);

            //Act
            var result = await controller.AddLeagueExtra(league);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expected, jsonResult.Value);
        }

        [Fact]
        public async Task GetEditLeague_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var LeagueId = new Fixture().Create<long>();
            var expected = new Fixture().Create<IList<League>>();
            leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = ("[" + LeagueId.ToString() + "]") }).Returns(expected);
            //Act
            var result = await controller.EditLeague(LeagueId);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("_Edit", viewResult.ViewName);
        }

        [Fact]
        public async Task PostEditLeague_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var league = new Fixture().Create<LeagueUpdateViewModel>();
            var expected = new Fixture().Create<CommandResult>();

            leagueService.UpdateLeaguesAsync(new UpdateLeagueCriteria
            {
                LeagueId = league.LeagueId,
                LeagueNameVN = league.LeagueNameVN,
                CountryVN = league.CountryVN?.ToUpper(CultureInfo.InvariantCulture)
            }).Returns(expected);
            leagueService.UpdateLeaguesExtraAsync(new League
            {
                LeagueId = league.LeagueId,
                IsMajor = league.IsMajor,
                Priority = league.Priority
            }).Returns(expected);
            //Act
            var result = await controller.EditLeague(league);
            // Assert

        }

        [Fact]
        public async Task GetAllLeagueById_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var LeagueId = new Fixture().Create<long>();
            var expected = new Fixture().Create<League>();
            leagueService.GetLeagueByIdAsync(LeagueId).Returns(expected);
            //Act
            var result = await controller.GetLeagueById(LeagueId);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expected, jsonResult.Value);

        }

        #region Helpers
        private ManagementLeagueController GetController()
        {
            leagueService = Substitute.For<ILeagueService>();
            var controller = new ManagementLeagueController(leagueService);

            return controller;
        }

        #endregion Helpers
    }
}
