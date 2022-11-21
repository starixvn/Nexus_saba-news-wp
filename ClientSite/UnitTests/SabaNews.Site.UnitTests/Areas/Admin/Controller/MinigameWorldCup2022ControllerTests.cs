namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class MinigameWorldCup2022ControllerTests : AdminController
    {
        private ILeagueService leagueService;
        private IExportExcelService excelService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public void MembershipEvent_WhenCalled_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();

            //Act
            var result = controller.MinigameWorldCup2022();

            // Assert
            Assert.IsType<ViewResult>(result);
        }

        [Fact]
        public async Task GetDataMinigameWorldCup2022_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<UserFilterMembershipEventModel>();
            var criteria = fixture.Create<GetDataMinigameCriteria>();
            var expected = fixture.Create<IEnumerable<MiniGame>>();
            leagueService.GetDataMinigameAsync(Arg.Do<GetDataMinigameCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var result = await controller.GetDataMinigameWorldCup2022(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsType<MinigameGridViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.MiniGames);
        }

        [Fact]
        public async Task ExportExcel_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var resultNumber = fixture.Create<int>();
            var criteria = fixture.Create<GetDataMinigameCriteria>();
            var excelModel = fixture.Create<IEnumerable<MiniGame>>();

            var expected = fixture.Create<IEnumerable<MiniGame>>();
            var expectedExcelResult = fixture.Create<byte[]>();
            leagueService.GetDataMinigameAsync(Arg.Do<GetDataMinigameCriteria>(x => criteria = x)).Returns(expected);
            excelService.ExportMinigameWorldCup2022(Arg.Do<IEnumerable<MiniGame>>(x => excelModel = x)).Returns(expectedExcelResult);
            //Act
            var result = await controller.ExportExcel() as FileContentResult;

            // Assert
            Assert.Equal(expectedExcelResult, result.FileContents);
            Assert.Equal("application/xlsx", result.ContentType);
            Assert.Equal("MiniGameWorldCup2022.xlsx", result.FileDownloadName);
        }

        #region Helpers
        private MinigameWorldCup2022Controller GetController()
        {
            leagueService = Substitute.For<ILeagueService>();
            excelService = Substitute.For<IExportExcelService>();
            var controller = new MinigameWorldCup2022Controller(excelService, leagueService);

            return controller;
        }

        #endregion Helpers
    }
}
