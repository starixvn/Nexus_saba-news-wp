namespace SabaNews.Site.UnitTests.Controllers
{
    using AutoFixture;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Controllers;
    using System;
    using System.Text;
    using Xunit;

    public class WhoamiControllerTests : Controller
    {
        [Fact]
        public void Index_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = new WhoamiController();
            var context = Substitute.For<HttpContext>();
            var request = Substitute.For<HttpRequest>();
            controller.ControllerContext = MockControllerContext();
            var content = new StringBuilder();
            content.Append($"Web name: {context.Request.Host.Host}{Environment.NewLine}"
                + $"Port: {context.Connection.LocalPort}{Environment.NewLine}"
                + $"Server IP: {context.Connection.LocalIpAddress}{Environment.NewLine}"
                + $"Server Time: {DateTime.Now}{Environment.NewLine}"
                + $"Client IP: {context.Connection.RemoteIpAddress}{Environment.NewLine}"
                + $"Request Scheme: {context.Request.Scheme}{Environment.NewLine}"
                + $"Request Path: {context.Request.Path}{Environment.NewLine}{Environment.NewLine}"
                + $"Request Headers:{Environment.NewLine}");

            foreach (var header in context.Request.Headers)
            {
                content.Append($"- {header.Key}: " +
                    $"{header.Value}{Environment.NewLine}");
            }
            var expected = content.ToString();

            //Act
            var actual = controller.Index();

            //Assert
            var viewResult = Assert.IsType<ContentResult>(actual);
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


    }
}
