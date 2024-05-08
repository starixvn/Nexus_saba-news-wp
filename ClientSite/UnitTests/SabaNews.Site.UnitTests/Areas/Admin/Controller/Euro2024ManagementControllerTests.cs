namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Options;
    using SabaNews.Models.Poll;
    using SabaNews.Models.ViewModel.Poll;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;

    public class Euro2024ManagementControllerTests : AdminController
    {
        private  IOptionService optionService;
        private  IPollService pollService;
        private  ITeamService teamService;
        private  IFileService fileService;
        private readonly IFixture fixture = new Fixture();


        [Fact]
        public async Task  Poll_WhenOptionValueIsNull_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();
            Option option = null;
            var expectedPolls = fixture.Create<IList<QuestionModel>>();
            optionService.GetOptionAsync(Arg.Do<string>(x => x = "key")).Returns(option);
            pollService.GetAllPollAsync().Returns(expectedPolls);

            //Act
            var result = await controller.Poll();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<PollAdminViewModel>(viewResult.Model);
            Assert.Equal(0, viewModel.SelectedQuestId);
            Assert.Equal("-------", viewModel.SelectedQuest);
        }

        [Fact]
        public async Task  Poll_WhenHaveOptionValue_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();
            var option = new Option{
                OptionId = 1,
                OptionValue = "1"
            };
            var expectedPolls = fixture.Create<IList<QuestionModel>>();
            expectedPolls.Add(new QuestionModel {Id = 1, Question = "Who is win?"});
            optionService.GetOptionAsync(Arg.Do<string>(x => x = "key")).Returns(option);
            pollService.GetAllPollAsync().Returns(expectedPolls);

            //Act
            var result = await controller.Poll();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(result);
            var viewModel = Assert.IsAssignableFrom<PollAdminViewModel>(viewResult.Model);
            Assert.Equal(1, viewModel.SelectedQuestId);
            Assert.Equal("Who is win?", viewModel.SelectedQuest);
        }


        #region Helpers
        private Euro2024ManagementController GetController()
        {
            optionService = Substitute.For<IOptionService>();
            pollService = Substitute.For<IPollService>();
            teamService = Substitute.For<ITeamService>();
            fileService = Substitute.For<IFileService>();
            var controller = new Euro2024ManagementController(optionService, pollService, teamService, fileService);

            return controller;
        }

        #endregion Helpers
    }
}
