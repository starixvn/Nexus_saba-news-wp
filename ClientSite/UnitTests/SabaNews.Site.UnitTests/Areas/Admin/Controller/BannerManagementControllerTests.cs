namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System;
    using System.Collections.Generic;
    using System.IO;
    using System.Net.Http;
    using System.Threading.Tasks;
    using Xunit;

    public class BannerManagementControllerTests : AdminController
    {
        private IBannerService bannerService;
        private IPathService pathService;
        private IConfiguration configuration;
        private string baseUrl;
        private readonly IFixture fixture = new Fixture();

        [Theory]
        [InlineData(BannerType.DesktopTop)]
        [InlineData(BannerType.DesktopTopNavigation)]
        [InlineData(BannerType.DesktopCenter)]
        [InlineData(BannerType.DesktopCenterNavigation)]
        [InlineData(BannerType.DesktopLeft)]
        [InlineData(BannerType.DesktopLeftNavigation)]
        [InlineData(BannerType.DesktopRight)]
        [InlineData(BannerType.DesktopRightNavigation)]
        [InlineData(BannerType.MobileTop)]
        [InlineData(BannerType.MobileTopNavigation)]
        [InlineData(BannerType.MobileCenter)]
        [InlineData(BannerType.MobileCenterNavigation)]
        [InlineData(BannerType.MobileBottom)]
        [InlineData(BannerType.MobileBottomNavigation)]
        public async Task UpdateInfo_WhenCalled_ReturnExpectedData(string type)
        {
            // Arrange
            var controller = GetController();
            var value = fixture.Create<string>();
            var expectedBanner = fixture.Create<BannerModel>();
            var expectedCommand = fixture.Create<CommandResult>();
            bannerService.GetBannerInfoAsync().Returns(expectedBanner);
            bannerService.UpdateBannerInfoAsync(Arg.Do<BannerModel>(x => x = expectedBanner)).Returns(expectedCommand);

            //Act
            var actual = await controller.UpdateInfo(value, type);

            //Assert
            var jsonResult = Assert.IsType<JsonResult>(actual);
            var result = Assert.IsAssignableFrom<CommandResult>(jsonResult.Value);
            Assert.Equal(expectedCommand, result);
        }

        [Fact]
        public void UpdateInfo_WhenCalled_ThrowException()
        {
            // Arrange
            var controller = GetController();
            var value = fixture.Create<string>();
            var type = fixture.Create<string>();
            var expectedBanner = fixture.Create<BannerModel>();
            bannerService.GetBannerInfoAsync().Returns(expectedBanner);

            // Act&Assert
            Assert.ThrowsAsync<InvalidOperationException>(() => controller.UpdateInfo(type, value));

        }

        [Fact]
        public async Task Index_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var expected = fixture.Create<BannerModel>();
            bannerService.GetBannerInfoAsync().Returns(expected);
            //Act
            var actual = await controller.Index();

            //Assert
            var viewResult = Assert.IsType<ViewResult>(actual);
            var viewModel = Assert.IsType<BannerModel>(viewResult.Model);
            Assert.Equal(expected, viewModel);
        }

        [Fact]
        public async Task UploadBanner_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            //var expected = fixture.Create<IFormFile>();
            var content = "Hello World from a Fake File";
            var fileName = "test.pdf";
            var stream = new MemoryStream();
            var writer = new StreamWriter(stream);
            using var headerValue = new MultipartFormDataContent();
            headerValue.Headers.Add("Content-Disposition", "form-data; name=\"json\"");
            writer.Write(content);
            writer.Flush();
            stream.Position = 0;
            var header = new HeaderDictionary();
            header.Add("Name", new string[1] { "Value" });
            IFormFile file = new FormFile(stream, 0, stream.Length, "id_from_form", fileName)
            {
                Headers = header,
                ContentType = "application/pdf"
            };
            var httpContext = new DefaultHttpContext();
            httpContext.Request.Headers.Add("Content-Type", "multipart/form-data");
            var enviroment = System.Environment.CurrentDirectory;
            string projectDirectory = Directory.GetParent(enviroment).Parent.FullName;
            var UnitTestDirectory = projectDirectory.Replace("\\bin", "");
            var directorty = "FilesTest";
            var filePath = Path.Combine(UnitTestDirectory, directorty);
            pathService.MapAbsoluteRootPath(Arg.Do<string>(x => x = directorty)).Returns(filePath);
            ////Act
            var actual = await controller.UploadBanner(file, "test");

           
        }

        #region Helpers
        private BannerManagementController GetController()
        {
            bannerService = Substitute.For<IBannerService>();
            pathService = Substitute.For<IPathService>();
            configuration = Substitute.For<IConfiguration>();
            var controller = new BannerManagementController(bannerService, pathService, configuration);

            return controller;
        }

        #endregion Helpers
    }
}
