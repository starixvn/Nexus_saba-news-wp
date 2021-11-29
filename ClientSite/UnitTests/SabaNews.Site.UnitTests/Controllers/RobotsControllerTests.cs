namespace SabaNews.Site.UnitTests.Controllers
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using Sabanews.Controllers;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;
    using System;
    using System.Collections.Generic;
    using System.Text;
    using System.Threading.Tasks;
    using Xunit;

    public class RobotsControllerTests : Controller
    {
        private IDataControlService dataControlService;
        private IConfiguration configuration;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public void RobotsTxt_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var domainName = "";
            var sb = new StringBuilder();
            sb.AppendLine("User-agent: *")
                .AppendLine("allow: /")
                .Append("Sitemap: ")
                .Append(domainName)
                .AppendLine("sitemap.xml")
                .Append("Sitemap: ")
                .Append(domainName)
                .AppendLine("sitemap-news.xml");

            var expected = sb.ToString();
            //Act
            var actual = controller.RobotsTxt();

            //Assert
            var viewResult = Assert.IsType<ContentResult>(actual);
            Assert.Equal(expected, viewResult.Content);
        }

        [Fact]
        public async Task Sitemap_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var expected = fixture.Create<IEnumerable<SitemapModel>>();
            dataControlService.GenerateSitmapXmlData().Returns(expected);
            var expectedSitemap = SitemapHelper.GetSitemapDocument(expected);
            //Act
            var actual = await controller.Sitemap();

            //Assert
            var viewResult = Assert.IsType<ContentResult>(actual);
            Assert.Equal(expectedSitemap, viewResult.Content);
        }

        [Fact]
        public async Task Sitemap_WhenMofidiedDateIsNull_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var expected = new List<SitemapModel>();
            expected.Add(new SitemapModel { ID = 1, Url = "url", ModifiedDate = DateTime.Now });
            expected.Add(new SitemapModel { ID = 2, Url = "url" });
            dataControlService.GenerateSitmapXmlData().Returns(expected);
            var expectedSitemap = SitemapHelper.GetSitemapDocument(expected);
            //Act
            var actual = await controller.Sitemap();

            //Assert
            var viewResult = Assert.IsType<ContentResult>(actual);
            Assert.Equal(expectedSitemap, viewResult.Content);
        }

        [Fact]
        public async Task SitemapNews_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var expected = fixture.Create<IEnumerable<NewsSitemapModel>>();
            dataControlService.GenerateNewsSitmapXmlData().Returns(expected);
            var expectedSitemap = SitemapHelper.GetSitemapNews(expected, "");
            //Act
            var actual = await controller.SitemapNews();

            //Assert
            var viewResult = Assert.IsType<ContentResult>(actual);
            Assert.Equal(expectedSitemap, viewResult.Content);
        }

        [Fact]
        public async Task RSS_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var controller = GetController();
            var testPosts = fixture.Create<List<Post>>();
            testPosts[0].Images = null;
            testPosts[0].FeatureImage = null;
            testPosts[0].PostDateGMT = DateTime.MinValue;
            var expected = (IEnumerable<Post>)testPosts;
            dataControlService.GenerateRSSData().Returns(expected);
            var expectedSitemap = RssHelper.GetRSSDocument(expected, "");
            //Act
            var actual = await controller.RSS();

            //Assert
            var viewResult = Assert.IsType<ContentResult>(actual);
            Assert.Equal(expectedSitemap, viewResult.Content);
        }

        #region Helpers
        private RobotsController GetController()
        {
            dataControlService = Substitute.For<IDataControlService>();
            configuration = Substitute.For<IConfiguration>();
            var controller = new RobotsController(dataControlService, configuration);

            return controller;
        }
        
        #endregion Helpers
    }
}
