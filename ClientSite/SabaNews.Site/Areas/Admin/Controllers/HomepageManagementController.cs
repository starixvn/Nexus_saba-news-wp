
namespace SabaNews.Site.Areas.Admin.Controllers
{
    using DeviceDetectorNET;
    using Microsoft.AspNetCore.Mvc;
    using Newtonsoft.Json;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Services;
    using SabaNews.Models.Home;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class HomepageManagementController : AdminController
    {
        private readonly IOptionService optionService;
        private readonly ILeagueService leagueService;
        public HomepageManagementController(IOptionService optionService, ILeagueService leagueService)
        {
            this.optionService = optionService;
            this.leagueService = leagueService;
        }
        public ActionResult Fixture()
        {
            return View();
        }
        [HttpPost]
        public async Task<ActionResult> GetFixtureGrid()
        {
            var result = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = HandleListData<FixtureHomepage>(result?.OptionValue);
            return PartialView("_FixtureGrid", listFixture);
        }

        [HttpPost]
        public async Task<ActionResult> GetFixtureToAdd()
        {
            var leagues = await leagueService.GetLeaguesAsync();
            return PartialView("_AddFixture", leagues);
        }

        [HttpPost]
        public async Task<ActionResult> AddFixture(long leagueId, long seasonId, string year, int order)
        {
            var result = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = HandleListData<FixtureHomepage>(result?.OptionValue);
            if (listFixture.Any())
            {
                var index = listFixture.FindIndex(item => item.LeagueId == leagueId);
                if (index >= 0)
                {
                    return Json(new CommandResult { Message = Message.DuplicateHomepageFixture });
                }
                var listDataAdd =  HandleOrder(listFixture, order);
                var maxId = listFixture.Max(item => item.Id);
                listDataAdd.Add(new FixtureHomepage { Id = maxId + 1, LeagueId = leagueId, SeasonId = seasonId, Year = year , Order = order });
                listFixture = listDataAdd;
            }
            else
            {
                listFixture.Add(new FixtureHomepage { Id = 1, LeagueId = leagueId, SeasonId = seasonId, Year = year, Order = order });
            }
            listFixture = listFixture.OrderBy(item => item.Order).ToList();
            var listFixtureJson = JsonConvert.SerializeObject(listFixture);
            var cmdResult = await optionService.UpdateOptionAsync(OptionKey.HompageFixture, listFixtureJson);
            return Json(cmdResult);
        }

        [HttpPost]
        public async Task<ActionResult> GetFixtureToEdit(int id)
        {
            var option = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = HandleListData<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == id);
            var seasons = await leagueService.GetSeasonByLeagueIdAsync(fixture.LeagueId);
            var league = await leagueService.GetLeagueByIdAsync(fixture.LeagueId);
            var model = new FixtureEditViewModel { League = league, Seasons = seasons, Fixture = fixture };
            return PartialView("_EditFixture", model);
        }

        [HttpPost]
        public async Task<ActionResult> EditFixture(int id, long seasonId, int order)
        {
            var option = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = HandleListData<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == id);
            var newListFixture = listFixture.Where(item => item.Id != id).ToList();
            var listDataEdit = HandleOrder(newListFixture, order);
            listDataEdit.Add(new FixtureHomepage { Id = fixture.Id, LeagueId = fixture.LeagueId, SeasonId = seasonId, Order = order });
            listDataEdit = listDataEdit.OrderBy(item => item.Order).ToList();
            var listFixtureJson = JsonConvert.SerializeObject(listDataEdit);
            var cmdResult = await optionService.UpdateOptionAsync(OptionKey.HompageFixture, listFixtureJson);
            return Json(cmdResult);
        }

        [HttpPost]
        public async Task<ActionResult> DeleteFixture(int id)
        {
            var option = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = HandleListData<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == id);
            var newListFixture = listFixture.Where(item => item.Id != id).ToList();
            var listDataRemove = HandleOrder(newListFixture, fixture.Order);
            var listFixtureJson = JsonConvert.SerializeObject(listDataRemove);
            var cmdResult = await optionService.UpdateOptionAsync(OptionKey.HompageFixture, listFixtureJson);
            return Json(cmdResult);
        }

        public static List<FixtureHomepage> HandleOrder(List<FixtureHomepage> fixtures, int order)
        {
            var listResult = new List<FixtureHomepage>();
            var listFixture = fixtures.OrderBy(item => item.Order).ToList();
            var currentOrder = 1;
            foreach (var item in fixtures)
            {
                if (currentOrder == order)
                {
                    ++currentOrder;
                }
                item.Order = currentOrder;
                listResult.Add(item);
                currentOrder++;
            }
            return listResult;
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