
namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;

    public class HomepageManagementController : AdminController
    {
        public HomepageManagementController()
        {
           
        }
        public ActionResult Fixture()
        {
            return View();
        }
    }
}