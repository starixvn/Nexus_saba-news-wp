namespace Sabanews.Controllers
{
    using System.Text;
    using Microsoft.AspNetCore.Mvc;

    public class RobotsController : Controller
    {
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
    }
}