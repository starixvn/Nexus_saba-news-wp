using AutoFixture;
using Microsoft.AspNetCore.Mvc;
using NSubstitute;
using SabaNews.Core.Services;
using SabaNews.Livescore.Models.League;
using SabaNews.Livescore.Services;
using SabaNews.Livescore.Services.Criteria.League;
using SabaNews.Models.ViewModel.User;
using SabaNews.Site.Areas.Admin.Controllers;
using System.Collections.Generic;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    public class ManagementLeagueControllerTests : AdminController
    {
        private readonly IBaseService baseService;
        private ILeagueService leagueService;
        private IFixture fixture = new Fixture();

        [Fact]
        public void MembershipEvent_WhenCalled_ReturnExpectedView()
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
            var param = fixture.Create<ManagementLeagueFilterModel>();
            var expected = fixture.Create<IList<League>>();
            leagueService.GetLeaguesAsync().Returns(expected);
            //Act
            var result = await controller.GetManagementLeagueData(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsType<ManagementLeagueGridViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Leagues);
        }

        [Fact]
        public async Task GetAllLeagues_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<ManagementAddLeagueFilterModel>();
            var expected = fixture.Create<IList<League>>();
            leagueService.GetAllLeaguesAsync().Returns(expected);
            //Act
            var result = await controller.GetAllLeagues(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsType<ManagementLeagueGridViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Leagues);
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
