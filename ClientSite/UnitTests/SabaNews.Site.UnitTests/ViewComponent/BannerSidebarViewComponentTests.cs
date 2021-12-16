namespace SabaNews.Site.UnitTests.ViewComponent
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc.ViewComponents;
    using NSubstitute;
    using SabaNews.Models.Banner;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Views.Shared.Components.BannerSideBar;
    using System.Threading.Tasks;
    using Xunit;

    public class BannerSidebarViewComponentTests
    {
        private IBannerService bannerService;

        [Fact]
        public async Task InvokeAsync_WhenCalled_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var fixture = new Fixture();
            var expectedBanner = fixture.Create<BannerModel>();
            bannerService.GetBannerInfoAsync().Returns(expectedBanner);
            //Act
            var actual = await component.InvokeAsync();

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<BannerModel>(viewResult.ViewData.Model);
            Assert.Equal(expectedBanner, viewModel);
        }

        #region Helpers
        private BannerSidebarViewComponent GetViewComponent()
        {
            bannerService = Substitute.For<IBannerService>();

            var component = new BannerSidebarViewComponent(bannerService);

            return component;
        }

        #endregion Helpers
    }
}
