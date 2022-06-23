namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

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
        public async Task<ActionResult> GetManagementLeagueData()
        {            
            return PartialView("_ManagementLeagueDataGrid");
        }

        public async Task<ActionResult> GetLeagueData()
        {
            return PartialView("");
        }
    }
}
