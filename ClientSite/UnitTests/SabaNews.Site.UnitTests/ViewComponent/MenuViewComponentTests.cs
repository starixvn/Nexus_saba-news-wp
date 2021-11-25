using AutoFixture;
using Microsoft.AspNetCore.Mvc.ViewComponents;
using NSubstitute;
using SabaNews.Core.Models;
using SabaNews.Core.Services;
using SabaNews.Models;
using SabaNews.Models.Banner;
using SabaNews.Services.Interfaces;
using SabaNews.Views.Shared.Components;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Site.UnitTests.ViewComponent
{
    public class MenuViewComponentTests
    {
        private IDataControlService dataControlService;
        private ISessionService sessionService;
        private IBannerService bannerService;

        [Fact]
        public async Task InvokeAsync_IsNotAuthenticate_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var fixture = new Fixture();
            var cateSlug = fixture.Create<string>();
            var page = fixture.Create<string>();
            var expectedMenu = fixture.Create<IEnumerable<Menu>>();
            var expectedBanner = fixture.Create<BannerModel>();
            dataControlService.GetMenuData().Returns(expectedMenu);
            bannerService.GetBannerInfoAsync().Returns(expectedBanner);

            //Act
            var actual = await component.InvokeAsync(cateSlug, page);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<MenuViewModel>(viewResult.ViewData.Model);
            Assert.Equal(expectedMenu, viewModel.Menus);
            Assert.Equal(expectedBanner, viewModel.Banner);
            Assert.False(viewModel.IsAdmin);
        }

        [Fact]
        public async Task InvokeAsync_IsAuthenticatedAndNotIsAdmin_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var fixture = new Fixture();
            sessionService.User = fixture.Create<UserSession>();
            var cateSlug = fixture.Create<string>();
            var page = fixture.Create<string>();
            var expectedMenu = fixture.Create<IEnumerable<Menu>>();
            var expectedBanner = fixture.Create<BannerModel>();
            dataControlService.GetMenuData().Returns(expectedMenu);
            bannerService.GetBannerInfoAsync().Returns(expectedBanner);

            //Act
            var actual = await component.InvokeAsync(cateSlug, page);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<MenuViewModel>(viewResult.ViewData.Model);
            Assert.Equal(expectedMenu, viewModel.Menus);
            Assert.Equal(expectedBanner, viewModel.Banner);
            Assert.False(viewModel.IsAdmin);
        }

        [Fact]
        public async Task InvokeAsync_IsAuthenticatedAndIsAdmin_ReturnExpectedViewResult()
        {
            //Arrange
            var component = GetViewComponent();
            var fixture = new Fixture();
            sessionService.User = new UserSession { Id = 1, Metadata = "administratoruser" };
            var cateSlug = fixture.Create<string>();
            var page = fixture.Create<string>();
            var expectedMenu = fixture.Create<IEnumerable<Menu>>();
            var expectedBanner = fixture.Create<BannerModel>();
            dataControlService.GetMenuData().Returns(expectedMenu);
            bannerService.GetBannerInfoAsync().Returns(expectedBanner);

            //Act
            var actual = await component.InvokeAsync(cateSlug, page);

            //Assert
            var viewResult = Assert.IsType<ViewViewComponentResult>(actual);
            var viewModel = Assert.IsType<MenuViewModel>(viewResult.ViewData.Model);
            Assert.Equal(expectedMenu, viewModel.Menus);
            Assert.Equal(expectedBanner, viewModel.Banner);
            Assert.True(viewModel.IsAdmin);
        }

        #region Helpers
        private MenuViewComponent GetViewComponent()
        {
            dataControlService = Substitute.For<IDataControlService>();
            sessionService = Substitute.For<ISessionService>();
            bannerService = Substitute.For<IBannerService>();
            var component = new MenuViewComponent(dataControlService, sessionService, bannerService);

            return component;
        }

        #endregion Helpers
    }
}
