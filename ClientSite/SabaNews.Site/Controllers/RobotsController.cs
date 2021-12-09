namespace Sabanews.Controllers
{
    using System.Text;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;

    public class RobotsController : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IConfiguration configuration;
        private readonly string domain;

        public RobotsController(IDataControlService dataControlService, IConfiguration configuration)
        {
            this.dataControlService = dataControlService;
            this.configuration = configuration;
            domain = configuration.GetValue<string>("BaseUrl");
        }

        [Route("/robots.txt")]
        public ContentResult RobotsTxt()
        {
            var sb = new StringBuilder();
            sb.AppendLine("User-agent: *")
                .AppendLine("allow: /")
                .Append("Sitemap: ")
                .Append(domain)
                .AppendLine("sitemap.xml")
                .Append("Sitemap: ")
                .Append(domain)
                .AppendLine("sitemap-news.xml");

            return Content(sb.ToString(), "text/plain", Encoding.UTF8);
        }

        [Route("sitemap.xml")]
        public async Task<ContentResult> Sitemap()
        {
            var data = await dataControlService.GenerateSitmapXmlData();
            string sitemap = SitemapHelper.GetSitemapDocument(data, domain);
            return Content(sitemap, "text/xml", Encoding.UTF8);
        }

        [Route("sitemap-news.xml")]
        public async Task<ContentResult> SitemapNews()
        {
            var data = await dataControlService.GenerateNewsSitmapXmlData();
            string sitemap = SitemapHelper.GetSitemapNews(data, domain);
            return Content(sitemap, "text/xml", Encoding.UTF8);
        }

        [Route("tin-moi-nhat.rss")]
        public async Task<ContentResult> RSS()
        {
            var data = await dataControlService.GenerateRSSData();
            string rss = RssHelper.GetRSSDocument(data, domain);
            return Content(rss, "text/xml", Encoding.UTF8);
        }
    }
}