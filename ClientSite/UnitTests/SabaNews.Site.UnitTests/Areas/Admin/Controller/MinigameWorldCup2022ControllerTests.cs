namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using SabaNews.Site.Extensions;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;

    public class MinigameWorldCup2022ControllerTests : AdminController
    {
        private ILeagueService leagueService;
        private IWorldCupService worldcupService;
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
            var criteriaMatch = new GetMatchBySeasonCriteria
            {
                LeagueId = WorldCup2022.LeagueIdWorldCup2022,
                SeasonId = WorldCup2022.SeasonIdWorldCup2022
            };
            var criteria = fixture.Create<GetMinigameUsersByStageCriteria>();
            var param = fixture.Create<UserFilterMembershipEventModel>();
            var expected = fixture.Create<IList<UserMinigameModel>>();
            var matches = fixture.Create<IList<Match>>();

            worldcupService.GetMinigameUsersAsync(criteria).Returns(expected);
            worldcupService.GetMatchBySeasonAsync(criteriaMatch).Returns(matches);

            expected = LivescoreHelper.CalculatorPointer(matches, expected);
            var miniGamesPaging = expected.Skip(param.PageSize * (param.Page - 1)).Take(param.PageSize).ToList();

            //Act
            var result = await controller.GetDataMinigameWorldCup2022(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsType<MinigameGridViewModel>(viewResult.Model);
            Assert.Equal(miniGamesPaging, viewModel.MiniGames);
        }

        [Fact]
        public async Task ExportExcel_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();

            var criteriaMatch = new GetMatchBySeasonCriteria
            {
                LeagueId = WorldCup2022.LeagueIdWorldCup2022,
                SeasonId = WorldCup2022.SeasonIdWorldCup2022
            };
            var criteria = fixture.Create<GetMinigameUsersByStageCriteria>();
            var param = fixture.Create<byte>();
            var expected = fixture.Create<IList<UserMinigameModel>>();
            var matches = fixture.Create<IList<Match>>();

            worldcupService.GetMinigameUsersAsync(criteria).Returns(expected);
            worldcupService.GetMatchBySeasonAsync(criteriaMatch).Returns(matches);

            expected = LivescoreHelper.CalculatorPointer(matches, expected);
            var excelModel = fixture.Create<IEnumerable<UserMinigameModel>>();
            var expectedExcelResult = fixture.Create<byte[]>();
            excelService.ExportMinigameWorldCup2022(Arg.Do<IEnumerable<UserMinigameModel>>(x => excelModel = x)).Returns(expectedExcelResult);
            //Act
            var result = await controller.ExportExcel(param) as FileContentResult;

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
            worldcupService = Substitute.For<IWorldCupService>();
            var controller = new MinigameWorldCup2022Controller(excelService, leagueService, worldcupService);

            return controller;
        }

        #endregion Helpers
    }
}
