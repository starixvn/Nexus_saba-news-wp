namespace SabaNews.Livescore.ScheduledTasks.Tests.Controller
{
    using System;
    using System.Net;
    using System.Text;
    using AutoFixture;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Helper;
    using SabaNews.Livescore.ScheduledTasks.Controllers;
    using Xunit;

    public class WhoamiControllerTests
    {
        [Fact]
        public void Index_WhenCalled_ReturnExpectedContent()
        {
            var fixture = new Fixture();
            var controller = new WhoamiController();

            controller.ControllerContext = new ControllerContext
            {
                HttpContext = new DefaultHttpContext()
            };
            controller.ControllerContext.HttpContext.Connection.LocalIpAddress = new IPAddress(fixture.Create<long>());
            controller.ControllerContext.HttpContext.Connection.RemoteIpAddress = new IPAddress(fixture.Create<long>());
            controller.ControllerContext.HttpContext.Request.Scheme = fixture.Create<string>();
            controller.ControllerContext.HttpContext.Request.Path = $"/{fixture.Create<string>()}";
            controller.ControllerContext.HttpContext.Request.Headers["device-id"] = "20317";

            var expected = GetContent(controller.ControllerContext.HttpContext);
            var actual = controller.Whoami();
            var viewResult = Assert.IsType<ContentResult>(actual);
            Assert.Equal(expected, viewResult.Content);
        }

        public static string GetContent(HttpContext context)
        {
            var content = new StringBuilder();
            content.Append($"Web name: {context.Request.Host.Host}{Environment.NewLine}"
                + $"Port: {context.Connection.LocalPort}{Environment.NewLine}"
                + $"Server IP: {context.Connection.LocalIpAddress}{Environment.NewLine}"
                + $"Server Time: {DateTimeUtilize.UtcNow()}{Environment.NewLine}"
                + $"Client IP: {context.Connection.RemoteIpAddress}{Environment.NewLine}"
                + $"Request Scheme: {context.Request.Scheme}{Environment.NewLine}"
                + $"Request Path: {context.Request.Path}{Environment.NewLine}{Environment.NewLine}"
                + $"Request Headers:{Environment.NewLine}");

            foreach (var header in context.Request.Headers)
            {
                content.Append($"- {header.Key}: " +
                    $"{header.Value}{Environment.NewLine}");
            }

            return content.ToString();
        }
    }
}