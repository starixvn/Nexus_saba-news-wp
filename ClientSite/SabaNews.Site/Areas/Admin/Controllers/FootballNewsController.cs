using Microsoft.AspNetCore.Mvc;

namespace SabaNews.Site.Areas.Admin.Controllers
{
    public class FootballNewsController : AdminController
    {
        public IActionResult Index()
        {
            return View();
        }
    }
}
