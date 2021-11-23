namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Banner;
    using SabaNews.Models.Banner;
    using System.Threading.Tasks;
    using Xunit;

    public class BannerServiceTests
    {
        private  IBaseService baseService;
        private  IConfiguration configuration;
        private  ISessionService sessionService;
        private const string DefaultDestopUrl = "images/Desktop_320x70.png";
        private const string DefaultMobileUrl = "images/Mobile_375x200.png";

        [Fact]
        public async Task GetBannerInfoAsync_WhenCalled_ReturnExpectedBannerModel()
        {
            //Arrange
            var service = GetBannerService();
            var fixture = new Fixture();
            var expected = fixture.Create<BannerModel>();
            var criteria = new GetBannerInfoCriteria();
            baseService.GetDataEntityAsync<BannerModel>(Arg.Do<GetBannerInfoCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetBannerInfoAsync();

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetBannerInfoAsync_WhenCalled_ReturnInitBannerInfo()
        {
            //Arrange
            var service = GetBannerService();
            var fixture = new Fixture();
            var baseUrl = fixture.Create<string>();
            var expected = new BannerModel { NavigationUrl = "NavigationUrl", MobileUrl = "", DesktopUrl = "" };
            var criteria = new GetBannerInfoCriteria();
            baseService.GetDataEntityAsync<BannerModel>(Arg.Do<GetBannerInfoCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetBannerInfoAsync();

            //Assert
            Assert.Equal(expected.NavigationUrl, actual.NavigationUrl);
            Assert.Equal(DefaultMobileUrl, actual.MobileUrl);
            Assert.Equal(DefaultDestopUrl, actual.DesktopUrl);
        }

        [Fact]
        public async Task UpdateBannerInfoAsync_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetBannerService();
            var fixture = new Fixture();
            var model = fixture.Create<BannerModel>();
            var expected = fixture.Create<CommandResult>();
            var command = new UpdateBannerInfoCommand();
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateBannerInfoCommand>(x => command = x)).Returns(expected);

            //Act
            var actual = await service.UpdateBannerInfoAsync(model);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(model.DesktopUrl, command.DesktopUrl);
            Assert.Equal(model.MobileUrl, command.MobileUrl);
            Assert.Equal(model.NavigationUrl, command.NaviagationUrl);

        }

        #region Helpers
        private BannerService GetBannerService()
        {
            baseService = Substitute.For<IBaseService>();
            configuration = Substitute.For<IConfiguration>();
            sessionService = Substitute.For<ISessionService>();
            sessionService.User = new UserSession { Id = 1 };
            var service = new BannerService(baseService, configuration, sessionService);

            return service;
        }

        #endregion Helpers
    }
}
