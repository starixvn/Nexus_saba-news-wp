namespace SabaNews.Site.UnitTests.Controllers
{
    using AutoFixture;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.AspNetCore.Mvc.ViewFeatures;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Controllers;
    using System;
    using System.Security.Claims;
    using System.Threading.Tasks;
    using Xunit;

    public class ExternalAuthenticationControllerTests : Controller
    {
        private readonly IFixture fixture = new Fixture();
        private IExternalAuthService externalAuthService;
        private ISessionService sessionService;
        private IUserService userService;

        [Fact]
        public void Login_WhenCalled_RunExpected()
        {
            // Arrange
            var controller = GetController();
            //Act
            controller.Login();
        }

        //[Fact]
        //public async Task ExternalResponse_WhenUserHaveAlready_ReturnExpectedViewResult()
        //{
        //    // Arrange
        //    var controller = GetController();
        //    var authResult = fixture.Create<AuthenticateResult>();
        //    var authParam = fixture.Create<string>();
        //    controller.ControllerContext = MockControllerContext();
        //    controller.ControllerContext.HttpContext.AuthenticateAsync(Arg.Do<string>(x => authParam = x)).Returns(authResult);
        //    sessionService.User = fixture.Create<UserSession>();
        //    var provider = fixture.Create<string>();
        //    var returnUrl = fixture.Create<string>();

        //    //Act
        //    var result = await controller.ExternalResponse(provider, returnUrl);

        //    // Assert
           
        //}

        #region Helpers
        private ExternalAuthenticationController GetController()
        {
            externalAuthService = Substitute.For<IExternalAuthService>();
            sessionService = Substitute.For<ISessionService>();
            userService = Substitute.For<IUserService>();
            var controller = new ExternalAuthenticationController(externalAuthService, sessionService, userService);
            controller.Url = Substitute.For<IUrlHelper>();
            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Scheme = fixture.Create<string>();
            return controller;
        }
        private IServiceProvider MockServiceProvider()
        {
            var authServiceMock = Substitute.For<IAuthenticationService>();
            var serviceProviderMock = Substitute.For<IServiceProvider>();
            serviceProviderMock.GetService(typeof(IAuthenticationService))
                .Returns(authServiceMock);
           
            return serviceProviderMock;
        }

        private ControllerContext MockControllerContext()
        {
            var request = Substitute.For<HttpRequest>();
            request.Host.Returns(new HostString("localhost", 8081));
            var httpContext = Substitute.For<HttpContext>();
            httpContext.Request.Returns(request);
            httpContext.RequestServices = MockServiceProvider();
            return new ControllerContext()
            {
                HttpContext = httpContext
            };
        }

        private void GetMockContext()
        {
            var controller = GetController();
            controller.Url = Substitute.For<IUrlHelper>();
            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Request.Scheme = fixture.Create<string>();
            
        }
        #endregion Helpers
    }
}
