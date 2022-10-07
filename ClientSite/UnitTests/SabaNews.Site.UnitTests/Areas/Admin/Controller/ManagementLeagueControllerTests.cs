namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using DeviceDetectorNET.Class.Device;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.ManagementLeague;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using SabaNews.Site.Areas.Admin.Controllers.Livescore;
    using System.Collections.Generic;
    using System.Globalization;
    using System.IO;
    using System.Threading.Tasks;
    using Xunit;
    public class ManagementLeagueControllerTests : AdminController
    {
        private ILeagueService leagueService;
        private IFileService fileService;

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
            Assert.Equal("~/Areas/Admin/Views/Livescore/ManagementLeague/_ManagementLeagueDataGrid.cshtml", viewResult.ViewName);
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
            Assert.Equal("~/Areas/Admin/Views/Livescore/ManagementLeague/_AddLeagueDataGrid.cshtml", viewResult.ViewName);
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
            var league = new LeagueUpdateViewModel
            {
                LeagueId = 1,
                CountryVN = "icon",
                IsDeletedIcon = true,
                IsMajor = true,
                LeagueNameVN = "league name",
                Priority = 1
            }; 

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
            Assert.Equal("~/Areas/Admin/Views/Livescore/ManagementLeague/_Edit.cshtml", viewResult.ViewName);
        }

        [Fact]
            public async Task PostEditLeague_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var league = new LeagueUpdateViewModel
            {
                LeagueId = 1,
                CountryVN = "icon",
                IsDeletedIcon = true,
                IsMajor = true,
                LeagueNameVN = "league name",
                Priority = 1
            };
            var expected = new Fixture().Create<CommandResult>();
            var expectedUploadResult = new CommandResult { Code = Constants.SuccessCode, Message = "url" };
            var stream = new MemoryStream();
            var header = new HeaderDictionary();
            header.Add("Name", new string[1] { "Value" });
            league.Icon = new FormFile(new MemoryStream(), 0, stream.Length, "id_from_form", "test.png")
            {
                Headers = header,
                ContentType = "application/pdf"
            };

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
            fileService.UploadFileAsync(Arg.Do<IFormFile>(x => x = league.Icon), Arg.Do<string>(x => x = UploadDir.LeagueUploadFolder)).Returns(expectedUploadResult);
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
            fileService = Substitute.For<IFileService>();
            var controller = new ManagementLeagueController(leagueService, fileService);

            return controller;
        }

        #endregion Helpers
    }
}
