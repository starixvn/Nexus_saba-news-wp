namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using SabaNews.Services.Interfaces;
    using SabaNews.Services;
    using SabaNews.Site.Areas.Admin.Controllers;
    using Microsoft.Extensions.Configuration;
    using Microsoft.AspNetCore.Mvc;
    using Xunit;
    using NSubstitute;
    using AutoFixture;
    using System.Threading.Tasks;
    using SabaNews.Models.Options;
    using SabaNews.Core.Constants;
    using System.Collections;
    using SabaNews.Models;
    using System.Collections.Generic;
    using System.Linq;
    using SabaNews.Core.Models;
    using Newtonsoft.Json;
    using OfficeOpenXml.FormulaParsing.Excel.Functions.Math;
    using SabaNews.Models.Home;
    using SabaNews.Models.Posts;
    using SabaNews.Site.Extensions;
    using System;

    public class FootballNewsControllerTests : AdminController
    {
        private IPostService postService;
        private IOptionService optionService;
        private IConfiguration configuration;
        private readonly Option optionLeft = new()
        {
            OptionId = 0,
            OptionName = "football_news_left",
            OptionValue = "{\"Category\":\"Asian Cup\",\"Slug\":\"asian-cup\",\"Url\":\"tin-the-thao/bong-da/quoc-te/asian-cup\",\"CreatedDate\":\"2023-01-30T06:22:22.6951709Z\"}"
        };
        private readonly Option optionCenter = new()
        {
            OptionId = 0,
            OptionName = "football_news_center",
            OptionValue = "{\"Category\":\"Asian Cup\",\"Slug\":\"asian-cup\",\"Url\":\"tin-the-thao/bong-da/quoc-te/asian-cup\",\"CreatedDate\":\"2023-01-30T06:22:22.6951709Z\"}"
        };
        private readonly Option optionRight = new()
        {
            OptionId = 0,
            OptionName = "football_news_right",
            OptionValue = "{\"Category\":\"Asian Cup\",\"Slug\":\"asian-cup\",\"Url\":\"tin-the-thao/bong-da/quoc-te/asian-cup\",\"CreatedDate\":\"2023-01-30T06:22:22.6951709Z\"}"
        };

        [Fact]
        public void FootballNews_WhenCalled_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();

            //Act
            var result = controller.Index();

            // Assert
            Assert.IsType<ViewResult>(result);
        }

        [Fact]
        public async Task GetDataFootBallGrid_WhenCalled_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();

            optionService.GetOptionAsync(OptionKey.FootballNewsLeft).Returns(optionLeft);
            optionService.GetOptionAsync(OptionKey.FootballNewsCenter).Returns(optionCenter);
            optionService.GetOptionAsync(OptionKey.FootballNewsRight).Returns(optionRight);

            //Act
            var result = await controller.GetDataFootBallGrid();

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Areas/Admin/Views/FootballNews/_FootballNewsDataGrid.cshtml", viewResult.ViewName);
        }

        [Fact]
        public async Task EditCategory_WhenCalled_ReturnExpectedView()
        {
            // Arrange
            var controller = GetController();
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = new Fixture().Create<IEnumerable<Menu>>();
            optionService.GetOptionAsync(OptionKey.FootballNewsLeft).Returns(optionLeft);
            optionService.GetOptionAsync(OptionKey.FootballNewsCenter).Returns(optionCenter);
            optionService.GetOptionAsync(OptionKey.FootballNewsRight).Returns(optionRight);
            postService.GetMenuAsync(menuName).Returns(menus);
            //Act
            var result = await controller.EditCategory(1);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(result);
            Assert.Equal("~/Areas/Admin/Views/FootballNews/_EditFootBall.cshtml", viewResult.ViewName);
        }

        #region Helpers
        private FootballNewsController GetController()
        {
            postService = Substitute.For<IPostService>();
            optionService = Substitute.For<IOptionService>();
            configuration = Substitute.For<IConfiguration>();
            var controller = new FootballNewsController(postService, configuration, optionService);

            return controller;
        }

        #endregion Helpers
    }
}
