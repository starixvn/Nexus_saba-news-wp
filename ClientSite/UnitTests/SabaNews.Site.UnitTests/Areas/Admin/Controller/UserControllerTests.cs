namespace SabaNews.Site.UnitTests.Areas.Admin.Controller
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.User;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Areas.Admin.Controllers;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class UserControllerTests : AdminController
    {
        private IUserService userSerive;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public void Index_WhenCalled_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();

            //Act
            var actual = controller.Index();

            // Assert
            var viewResult = Assert.IsType<ViewResult>(actual);
            var viewModel = Assert.IsType<UserFilterViewModel>(viewResult.Model);
            Assert.Equal(DateTime.Now.Date, viewModel.ToDate.Date);
        }

        [Fact]
        public async Task GetDataUser_SearchIsNotNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<UserFilterViewModel>();
            var criteria = fixture.Create<GetUserReportCriteria>();
            var expected = fixture.Create<IEnumerable<UserSession>>();

            userSerive.GetUserReportAsync(Arg.Do<GetUserReportCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var actual = await controller.GetDataUser(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(actual);
            var viewModel = Assert.IsType<UserGridViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Users);
            Assert.Equal("_UserDataGrid", viewResult.ViewName);
            Assert.Equal(param.Search, criteria.Search);
        }

        [Fact]
        public async Task GetDataUser_SearchIsNull_ReturnExpectedViewResult()
        {
            // Arrange
            var controller = GetController();
            var param = fixture.Create<UserFilterViewModel>();
            var criteria = fixture.Create<GetUserReportCriteria>();
            var expected = fixture.Create<IEnumerable<UserSession>>();
            param.Search = null;

            userSerive.GetUserReportAsync(Arg.Do<GetUserReportCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var actual = await controller.GetDataUser(param);

            // Assert
            var viewResult = Assert.IsType<PartialViewResult>(actual);
            var viewModel = Assert.IsType<UserGridViewModel>(viewResult.Model);
            Assert.Equal(expected, viewModel.Users);
            Assert.Equal("_UserDataGrid", viewResult.ViewName);
            Assert.Equal(string.Empty, criteria.Search);
        }



        #region Helpers
        private UserController GetController()
        {
            userSerive = Substitute.For<IUserService>();
            var controller = new UserController(userSerive);

            return controller;
        }
        #endregion Helpers
    }
}
