namespace Sabanews.Controllers
{
    using System.Text;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;

    public class RobotsController : Controller
    {
        private readonly IDataControlService dataControlService;

        public RobotsController(IDataControlService dataControlService)
        {
            this.dataControlService = dataControlService;
        }

        [Route("/robots.txt")]
        public ContentResult RobotsTxt()
        {
            var sb = new StringBuilder();
            sb.AppendLine("User-agent: *")
                .AppendLine("allow: /")
                .Append("sitemap: ")
                .Append(Request.Scheme)
                .Append("://")
                .Append(Request.Host)
                .AppendLine("/sitemap.xml");

            return Content(sb.ToString(), "text/plain", Encoding.UTF8);
        }

        [Route("sitemap.xml")]
        public async Task<ContentResult> Sitemap()
        {
            var data = await dataControlService.GenerateSitmapXmlData();
            string sitemap = SitemapHelper.GetSitemapDocument(data);
            return Content(sitemap, "text/xml", Encoding.UTF8);
        }
    }
}