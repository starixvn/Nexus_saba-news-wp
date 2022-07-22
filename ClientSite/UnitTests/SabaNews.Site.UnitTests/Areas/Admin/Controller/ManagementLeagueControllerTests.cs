namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System.Collections.Generic;
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
            var param = new Fixture().Create<ManagementAddLeagueFilterModel>();
            var expected = new Fixture().Create<IList<League>>();
            leagueService.GetAllLeaguesAsync().Returns(expected);
            //Act
            var result = await controller.GetAllLeagues(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("_AddLeagueDataGrid", viewResult.ViewName);
        }

        [Fact]
        public async Task ChangeMajor_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var leagueId = new Fixture().Create<int>();
            var priority = new Fixture().Create<int>();
            var isMajor = new Fixture().Create<bool>();
            var expected = new Fixture().Create<CommandResult>();
            leagueService.UpdateLeaguesExtraAsync(leagueId, priority, isMajor).Returns(expected);
            //Act
            var result = await controller.ChangeMajor(leagueId, priority, isMajor);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expected, jsonResult.Value);
        }


        [Fact]
        public async Task ChangePriority_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var leagueId = new Fixture().Create<int>();
            var priority = new Fixture().Create<int>();
            var isMajor = new Fixture().Create<bool>();
            var expected = new Fixture().Create<CommandResult>();
            leagueService.UpdateLeaguesExtraAsync(leagueId, priority, isMajor).Returns(expected);
            //Act
            var result = await controller.ChangePriority(leagueId, priority, isMajor);

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
            var leagueId = new Fixture().Create<int>();
            var expected = new Fixture().Create<CommandResult>();
            leagueService.InsertLeaguesExtraAsync(leagueId).Returns(expected);
            //Act
            var result = await controller.AddLeagueExtra(leagueId);

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
