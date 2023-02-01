namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System;
    using System.IO;
    using System.Net.Http;
    using System.Threading.Tasks;
    using Xunit;

    public class BannerManagementControllerTests : AdminController
    {
        private IBannerService bannerService;
        private IFileService fileService;
        private ISessionService sessionService;
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
            var user = fixture.Create<UserSession>();
            var value = fixture.Create<string>();
            var expectedBanner = fixture.Create<BannerModel>();
            var expectedCommand = fixture.Create<CommandResult>();
            sessionService.User.Returns(user);
            bannerService.GetBannerInfoAsync().Returns(expectedBanner);
            bannerService.UpdateBannerInfoAsync(Arg.Do<BannerModel>(x => x = expectedBanner), user.Id).Returns(expectedCommand);

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
            var expectedCommand = fixture.Create<CommandResult>();
            bannerService.GetBannerInfoAsync().Returns(expected);
            //Act
            var actual = await controller.Index();

            //Assert
            var viewResult = Assert.IsType<ViewResult>(actual);
            var viewModel = Assert.IsType<BannerModel>(viewResult.Model);
            Assert.Equal(expected, viewModel);
        }

        [Theory]
        [InlineData(BannerType.DesktopTop)]
        [InlineData(BannerType.DesktopCenter)]
        [InlineData(BannerType.DesktopLeft)]
        [InlineData(BannerType.DesktopRight)]
        [InlineData(BannerType.MobileTop)]
        [InlineData(BannerType.MobileCenter)]
        [InlineData(BannerType.MobileBottom)]
        public async Task UploadBanner_WhenCalled_ReturnExpectedViewResult(string type)
        {
            // Arrange
            var controller = GetController();
            var expectedUploadResult = new UploadResult { Code = Constants.SuccessCode, Message = "url" };
            var stream = new MemoryStream();
            var header = new HeaderDictionary();
            header.Add("Name", new string[1] { "Value" });
            var file = new FormFile(new MemoryStream(), 0, stream.Length, "id_from_form", "test.png")
            {
                Headers = header,
                ContentType = "application/pdf"
            };
            var user = fixture.Create<UserSession>();
            var expectedBanner = fixture.Create<BannerModel>();
            var expectedCommand = fixture.Create<CommandResult>();
            sessionService.User.Returns(user);

            bannerService.GetBannerInfoAsync().Returns(expectedBanner);
            fileService.UploadFileAsync(Arg.Do<IFormFile>(x => x = file), Arg.Do<string>(x => x = UploadDir.BannerUploadFolder)).Returns(expectedUploadResult);
            bannerService.UpdateBannerInfoAsync(Arg.Do<BannerModel>(x => x = expectedBanner), user.Id).Returns(expectedCommand);
            //Act
            var actual = await controller.UploadBanner(file, type);

            //Assert
            var jsonResult = Assert.IsType<JsonResult>(actual);
            var result = Assert.IsAssignableFrom<CommandResult>(jsonResult.Value);
            Assert.Equal(expectedCommand, result);
        }

        [Fact]
        public void UploadBanner_WhenCalled_ThrowException()
        {
            // Arrange
            var controller = GetController();
            var expectedUploadResult = new UploadResult { Code = Constants.SuccessCode, Message = "url", AbsoluteUrl = "url" };
            var stream = new MemoryStream();
            var header = new HeaderDictionary();
            header.Add("Name", new string[1] { "Value" });
            var file = new FormFile(new MemoryStream(), 0, stream.Length, "id_from_form", "test.png")
            {
                Headers = header,
                ContentType = "application/pdf"
            };
            var user = fixture.Create<UserSession>();
            var expectedBanner = fixture.Create<BannerModel>();
            sessionService.User.Returns(user);
            var type = fixture.Create<string>();

            fileService.UploadFileAsync(Arg.Do<IFormFile>(x => x = file), Arg.Do<string>(x => x = UploadDir.BannerUploadFolder)).Returns(expectedUploadResult);


            // Act&Assert
            Assert.ThrowsAsync<InvalidOperationException>(() => controller.UploadBanner(file, type));

        }

        #region Helpers
        private BannerManagementController GetController()
        {
            bannerService = Substitute.For<IBannerService>();
            fileService = Substitute.For<IFileService>();
            sessionService = Substitute.For<ISessionService>();
            var controller = new BannerManagementController(bannerService, sessionService, fileService);

            return controller;
        }

        #endregion Helpers
    }
}
