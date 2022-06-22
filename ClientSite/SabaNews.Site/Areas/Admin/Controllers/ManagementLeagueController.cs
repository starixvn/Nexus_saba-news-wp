namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;

    public class ManagementLeagueController : AdminController
    {
        private readonly IUserService userSerive;

        public ManagementLeagueController(IUserService userSerive)
        {
            this.userSerive = userSerive;
        }
        public IActionResult Index()
        {
            return View();
        }

    }
}
