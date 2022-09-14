namespace SabaNews.Site.UnitTests.Areas.Admin.Controller.Livescore
{
    using AutoFixture;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models.Livescore.MatchManagement;
    using SabaNews.Models.Livescore.StandingManagement;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers.Livescore;
    using System.Collections.Generic;
    using System.IO;
    using System.Threading.Tasks;
    using Xunit;

    public class StandingManagementControllerTests
    {
        private ILeagueService leagueService;
        private IMatchService matchService;
        private IFileService fileService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task Index_WhenCalled_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();
            var expectedLeague = fixture.Create<IList<League>>();

            leagueService.GetLeaguesAsync().Returns(expectedLeague);
            //Act
            var result = await controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsType<MatchManageModel>(viewResult.Model);
            Assert.Equal(expectedLeague, viewModel.Leagues);
            Assert.IsType<ViewResult>(result);
        }

        [Fact]
        public async Task GetDataGrid_WhenCalled_ReturnExpectedViewData()
        {
            // Arrange
            var controller = GetController();
            var leagueId = fixture.Create<int>();
            var criteria = fixture.Create<GetLeagueSeasonStandingCriteria>();
            var model = fixture.Create<StandingFilterModel>();
            var expected = new List<SeasonStandings>();
            expected.Add(new SeasonStandings { GroupName = "A" });
            expected.Add(new SeasonStandings { GroupName = "B" });
            expected.Add(new SeasonStandings { GroupName = "A" });
            var expectGroups = new List<StandingGroupModel>();
            expectGroups.Add(new StandingGroupModel { GroupName = "A", Standings = new List<SeasonStandings>() });
            expectGroups.Add(new StandingGroupModel { GroupName = "B", Standings = new List<SeasonStandings>() });
            leagueService.GetLeagueSeasonStanding(Arg.Do<GetLeagueSeasonStandingCriteria>(x => x = criteria)).Returns(expected);
            //Act
            var result = await controller.GetDataGrid(model);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<StandingManageGridViewModel>(viewResult.Model);
            Assert.Equal(2, viewModel.Groups.Count);
        }

        [Fact]
        public async Task EditMatch_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var seasonId = fixture.Create<int>();
            var stageId = fixture.Create<int>();
            var teamId = fixture.Create<int>();
            var criteria = fixture.Create<GetLeagueSeasonStandingDetailCriteria>();
            var expectedStanding = fixture.Create<SeasonStandings>();

            leagueService.GetLeagueSeasonStandingDetailAsync(Arg.Do<GetLeagueSeasonStandingDetailCriteria>(x => x = criteria)).Returns(expectedStanding);
            //Act
            var result = await controller.EditStanding(seasonId, stageId, teamId);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<SeasonStandings>(viewResult.Model);
            Assert.Equal(expectedStanding, viewModel);
        }

        [Fact]
        public async Task EditStanding_WhenNotUploadIcon_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var model = new StandingUpdateModel { };
            model.IsDeletedIcon = true;
            var cmd = fixture.Create<UpdateMatchCommand>();
            var criteria = fixture.Create<GetLeagueSeasonStandingDetailCriteria>();
            var expectedStanding = fixture.Create<SeasonStandings>();
            var expectedUpdateResult = fixture.Create<CommandResult>();

            leagueService.GetLeagueSeasonStandingDetailAsync(Arg.Do<GetLeagueSeasonStandingDetailCriteria>(x => x = criteria)).Returns(expectedStanding);
            leagueService.UpdateStandingAsync(Arg.Do<StandingUpdateModel>(x => x = model)).Returns(expectedUpdateResult);
            //Act
            var result = await controller.EditStanding(model);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expectedUpdateResult, jsonResult.Value);
        }

        [Fact]
        public async Task EditStanding_WhenUploadIconSuccessful_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var model = new StandingUpdateModel { };
            model.IsDeletedIcon = true;
            var stream = new MemoryStream();
            var header = new HeaderDictionary();
            header.Add("Name", new string[1] { "Value" });
            model.Icon = new FormFile(new MemoryStream(), 0, stream.Length, "id_from_form", "test.png")
            {
                Headers = header,
                ContentType = "application/pdf"
            };
            var cmd = fixture.Create<UpdateMatchCommand>();
            var criteria = fixture.Create<GetLeagueSeasonStandingDetailCriteria>();
            var expectedStanding = fixture.Create<SeasonStandings>();
            var expectedUpdateResult = fixture.Create<CommandResult>();
            var expectedUploadResult = new CommandResult { Code = Constants.SuccessCode, Message = "url" };

            leagueService.GetLeagueSeasonStandingDetailAsync(Arg.Do<GetLeagueSeasonStandingDetailCriteria>(x => x = criteria)).Returns(expectedStanding);
            leagueService.UpdateStandingAsync(Arg.Do<StandingUpdateModel>(x => x = model)).Returns(expectedUpdateResult);
            fileService.UploadFileAsync(Arg.Do<IFormFile>(x => x = model.Icon), Arg.Do<string>(x => x = UploadDir.MatchTeamUploadFolder)).Returns(expectedUploadResult);
            //Act
            var result = await controller.EditStanding(model);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expectedUpdateResult, jsonResult.Value);
        }

        [Fact]
        public async Task EditStanding_WhenUploadIconFailed_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var model = new StandingUpdateModel { };
            model.IsDeletedIcon = true;
            var stream = new MemoryStream();
            var header = new HeaderDictionary();
            header.Add("Name", new string[1] { "Value" });
            model.Icon = new FormFile(new MemoryStream(), 0, stream.Length, "id_from_form", "test.png")
            {
                Headers = header,
                ContentType = "application/pdf"
            };
            var cmd = fixture.Create<UpdateMatchCommand>();
            var criteria = fixture.Create<GetLeagueSeasonStandingDetailCriteria>();
            var expectedStanding = fixture.Create<SeasonStandings>();
            var expectedUpdateResult = fixture.Create<CommandResult>();
            var expectedUploadResult = new CommandResult { Message = "error" };

            leagueService.GetLeagueSeasonStandingDetailAsync(Arg.Do<GetLeagueSeasonStandingDetailCriteria>(x => x = criteria)).Returns(expectedStanding);
            leagueService.UpdateStandingAsync(Arg.Do<StandingUpdateModel>(x => x = model)).Returns(expectedUpdateResult);
            fileService.UploadFileAsync(Arg.Do<IFormFile>(x => x = model.Icon), Arg.Do<string>(x => x = UploadDir.MatchTeamUploadFolder)).Returns(expectedUploadResult);
            //Act
            var result = await controller.EditStanding(model);

            // Assert
            var jsonResult = Assert.IsType<JsonResult>(result);
            Assert.Equal(expectedUpdateResult, jsonResult.Value);
        }


        #region Helpers
        private StandingManagementController GetController()
        {
            leagueService = Substitute.For<ILeagueService>();
            matchService = Substitute.For<IMatchService>();
            fileService = Substitute.For<IFileService>();
            var controller = new StandingManagementController(leagueService, matchService, fileService);

            return controller;
        }

        #endregion Helpers
    }
}
