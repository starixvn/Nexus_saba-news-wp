
namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Models.Home;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class HomepageManagementController : AdminController
    {
        private readonly IOptionService optionService;
        private const string FixtureKey = "homepage_fixture";
        public HomepageManagementController(IOptionService optionService)
        {
            this.optionService = optionService;
        }
        public ActionResult Fixture()
        {
            return View();
        }

        public async Task<ActionResult> GetFixtureGrid()
        {
            var result = await optionService.GetOptionAsync(FixtureKey);
            var list = HandleListData<FixtureHomepage>(result?.OptionValue);
            return PartialView("_FixtureGrid", list);
        }
        public static List<T> HandleListData<T>(string data)
        {
            var result = new List<T>();
            if (data == null)
            {
                return result;
            }
            result = JsonConvert.DeserializeObject<List<T>>(data);
            return result;
        }
    }
}