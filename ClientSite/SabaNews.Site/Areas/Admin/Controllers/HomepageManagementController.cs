namespace SabaNews.Site.Areas.Admin.Controllers
{
    using DeviceDetectorNET;
    using Microsoft.AspNetCore.Mvc;
    using Newtonsoft.Json;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
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
            var option = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var model = new FixtureGridViewModel { ListFixture = listFixture };
            if (listFixture.Any())
            {
                long[] leagueIds = listFixture.Select(item => item.LeagueId).ToArray();
                var leagueIdsJson = JsonConvert.SerializeObject(leagueIds);
                var leagues = await leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = leagueIdsJson });
                model.Leagues = leagues;
            }
            return PartialView("_FixtureGrid", model);
        }

        [HttpPost]
        public async Task<ActionResult> GetFixtureToAdd()
        {
            var leagues = await leagueService.GetLeaguesAsync();
            return PartialView("_AddFixture", leagues);
        }

        [HttpPost]
        public async Task<ActionResult> AddFixture(UpdateFixtureModel model)
        {
            var result = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(result?.OptionValue);
            if (listFixture.Any())
            {
                var index = listFixture.FindIndex(item => item.LeagueId == model.LeagueId);
                if (index >= 0)
                {
                    return Json(new CommandResult { Message = Message.DuplicateHomepageFixture });
                }
                var listDataAdd =  HandleOrder(listFixture, model.Order);
                var maxId = listFixture.Max(item => item.Id);
                listDataAdd.Add(new FixtureHomepage { Id = maxId + 1, LeagueId = model.LeagueId, SeasonId = model.SeasonId, Year = model.Year , Order = model.Order });
                listFixture = listDataAdd;
            }
            else
            {
                listFixture.Add(new FixtureHomepage { Id = 1, LeagueId = model.LeagueId, SeasonId = model.SeasonId, Year = model.Year, Order = model.Order });
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
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == id);
            var seasons = await leagueService.GetSeasonByLeagueIdAsync(fixture.LeagueId);
            var league = await leagueService.GetLeagueByIdAsync(fixture.LeagueId);
            var model = new FixtureEditViewModel { League = league, Seasons = seasons, Fixture = fixture };
            return PartialView("_EditFixture", model);
        }

        [HttpPost]
        public async Task<ActionResult> EditFixture(UpdateFixtureModel model)
        {
            var option = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == model.Id);
            var newListFixture = listFixture.Where(item => item.Id != model.Id).ToList();
            var listDataEdit = HandleOrder(newListFixture, model.Order);
            listDataEdit.Add(new FixtureHomepage { Id = fixture.Id, LeagueId = fixture.LeagueId, SeasonId = model.SeasonId, Year = model.Year, Order = model.Order });
            listDataEdit = listDataEdit.OrderBy(item => item.Order).ToList();
            var listFixtureJson = JsonConvert.SerializeObject(listDataEdit);
            var cmdResult = await optionService.UpdateOptionAsync(OptionKey.HompageFixture, listFixtureJson);
            return Json(cmdResult);
        }

        [HttpPost]
        public async Task<ActionResult> DeleteFixture(int id)
        {
            var option = await optionService.GetOptionAsync(OptionKey.HompageFixture);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
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
       
    }
}