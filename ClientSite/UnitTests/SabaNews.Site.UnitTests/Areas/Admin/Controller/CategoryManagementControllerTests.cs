namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Category;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Models;
    using SabaNews.Models.Banner;
    using SabaNews.Models.Categories;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System.Collections.Generic;
    using System.IO;
    using System.Threading.Tasks;
    using Xunit;

    public class CategoryManagementControllerTests : AdminController
    {
        private  IDataControlService dataControlService;
        private  ICategoryService categoryService;
        private  ILeagueService leagueService;
        private  ISessionService sessionService;
        private  IFixture fixture = new Fixture();

        [Fact]
        public async Task Index_WhenValueIsNotNull_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var user = fixture.Create<UserSession>();
            var value = fixture.Create<string>();
            var type = fixture.Create<string>();
            var expectedMenu = fixture.Create<IEnumerable<Menu>>();
            var expectedCate = fixture.Create<IList<CateLivescore>>();
            var expectedLeagues = fixture.Create<IList<League>>();
            dataControlService.GetMenuData(Arg.Do<string>(x => x = type)).Returns(expectedMenu);
            categoryService.GetCateLivescoreAsync().Returns(expectedCate);
            leagueService.GetLeaguesAsync().Returns(expectedLeagues);

            //Act
            var actual = await controller.Index();

            //Assert
            var result = Assert.IsType<ViewResult>(actual);
            var viewModel = Assert.IsType<CateLivescoreManageViewModel>(result.Model);
            Assert.Equal(expectedMenu, viewModel.Menus);
            Assert.Equal(expectedCate, viewModel.Cates);
            Assert.Equal(expectedLeagues, viewModel.Leagues);
        }

        [Fact]
        public async Task UpdateCate_WhenValueIsNull_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var model = fixture.Create<UpdateCateLivescoreCommand>();
            var cmd = fixture.Create<UpdateCateLivescoreCommand>();
            var expectedResult = fixture.Create<CommandResult>();
            var user = fixture.Create<UserSession>();
            sessionService.User.Returns(user);
            categoryService.UpdateCateLivescoreAsync(Arg.Do<UpdateCateLivescoreCommand>(x => x = cmd)).Returns(expectedResult);

            //Act
            var actual = await controller.UpdateCate(model);

            //Assert
            var jsonResult = Assert.IsType<JsonResult>(actual);
            var result = Assert.IsAssignableFrom<CommandResult>(jsonResult.Value);
            Assert.Equal(expectedResult, result);
        }


        #region Helpers
        private CategoryManagementController GetController()
        {
            dataControlService = Substitute.For<IDataControlService>();
            leagueService = Substitute.For<ILeagueService>();
            categoryService = Substitute.For<ICategoryService>();
            sessionService = Substitute.For<ISessionService>();
            var controller = new CategoryManagementController(dataControlService, categoryService, leagueService, sessionService);

            return controller;
        }

        #endregion Helpers
    }
}
