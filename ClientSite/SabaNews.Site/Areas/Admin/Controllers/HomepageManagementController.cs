namespace SabaNews.Site.Areas.Admin.Controllers
{
    using DeviceDetectorNET;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using Newtonsoft.Json;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Home;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;
    using System;
    using System.Collections.Generic;
    using System.Configuration;
    using System.Linq;
    using System.Threading.Tasks;

    public class HomepageManagementController : AdminController
    {
        private readonly IOptionService optionService;
        private readonly ILeagueService leagueService;
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        public HomepageManagementController(IOptionService optionService, 
            ILeagueService leagueService, 
            IPostService postService, 
            IConfiguration configuration)
        {
            this.optionService = optionService;
            this.leagueService = leagueService;
            this.postService = postService;
            this.configuration = configuration;
        }
        public ActionResult Fixture()
        {
            return View();
        }

        public ActionResult Standing()
        {
            return View();
        }
        [HttpPost]
        public async Task<ActionResult> GetLeagueGrid(bool isFixtureOption)
        {
            var optionKey = GetKeyOption(isFixtureOption);
            var option = await optionService.GetOptionAsync(optionKey);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var model = new FixtureGridViewModel { ListFixture = listFixture };
            if (listFixture.Any())
            {
                long[] leagueIds = listFixture.Select(item => item.LeagueId).ToArray();
                var leagueIdsJson = JsonConvert.SerializeObject(leagueIds);
                var leagues = await leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = leagueIdsJson });
                model.Leagues = leagues;
            }
            return PartialView("_LeagueGrid", model);

        }

        [HttpPost]
        public async Task<ActionResult> GetLeagueToAdd(bool isFixtureOption)
        {
            var optionKey = GetKeyOption(isFixtureOption);
            var leagues = await leagueService.GetLeaguesAsync();
            var option = await optionService.GetOptionAsync(optionKey);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var maxOrder = listFixture.Any() ? listFixture.Max(item => item.Order) : 0;
            ViewBag.Order = maxOrder + 1;
            return PartialView("_AddLeague", leagues);
        }

        [HttpPost]
        public async Task<ActionResult> AddLeague(UpdateFixtureModel model)
        {
            var optionKey = GetKeyOption(model.IsFixtureOption);
            var result = await optionService.GetOptionAsync(optionKey);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(result?.OptionValue);
            if (listFixture.Any())
            {
                var index = listFixture.FindIndex(item => item.LeagueId == model.LeagueId);
                if (index >= 0)
                {
                    return Json(new CommandResult { Message = Message.DuplicateHomepageFixture });
                }
                var listDataAdd = HandleOrder(listFixture, model.Order);
                var maxId = listFixture.Max(item => item.Id);
                listDataAdd.Add(new FixtureHomepage
                {
                    Id = maxId + 1,
                    LeagueId = model.LeagueId,
                    SeasonId = model.SeasonId,
                    Order = model.Order,
                    CreatedDate = DateTime.UtcNow

                });
                listFixture = listDataAdd;
            }
            else
            {
                listFixture.Add(new FixtureHomepage
                {
                    Id = 1,
                    LeagueId = model.LeagueId,
                    SeasonId = model.SeasonId,
                    Order = model.Order,
                    CreatedDate = DateTime.UtcNow
                });
            }
            listFixture = listFixture.OrderBy(item => item.Order).ToList();
            var listFixtureJson = JsonConvert.SerializeObject(listFixture);
            var cmdResult = await optionService.UpdateOptionAsync(optionKey, listFixtureJson);
            return Json(cmdResult);
        }

        [HttpPost]
        public async Task<ActionResult> GetLeagueToEdit(int id, bool isFixtureOption)
        {
            var optionKey = GetKeyOption(isFixtureOption);
            var option = await optionService.GetOptionAsync(optionKey);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == id);
            var seasons = await leagueService.GetSeasonByLeagueIdAsync(fixture.LeagueId);
            var league = await leagueService.GetLeagueByIdAsync(fixture.LeagueId);
            var model = new FixtureEditViewModel { League = league, Seasons = seasons, Fixture = fixture };
            return PartialView("_EditLeague", model);
        }

        [HttpPost]
        public async Task<ActionResult> EditLeague(UpdateFixtureModel model)
        {
            var optionKey = GetKeyOption(model.IsFixtureOption);
            var option = await optionService.GetOptionAsync(optionKey);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == model.Id);
            var newListFixture = listFixture.Where(item => item.Id != model.Id).ToList();
            var listDataEdit = HandleOrder(newListFixture, model.Order);
            listDataEdit.Add(new FixtureHomepage { 
                Id = fixture.Id, 
                LeagueId = fixture.LeagueId,
                SeasonId = fixture.SeasonId, 
                Order = model.Order,
                CreatedDate = fixture.CreatedDate
            });
            listDataEdit = listDataEdit.OrderBy(item => item.Order).ToList();
            var listFixtureJson = JsonConvert.SerializeObject(listDataEdit);
            var cmdResult = await optionService.UpdateOptionAsync(optionKey, listFixtureJson);
            return Json(cmdResult);
        }

        [HttpPost]
        public async Task<ActionResult> DeleteLeague(int id, bool isFixtureOption)
        {
            var optionKey = GetKeyOption(isFixtureOption);
            var option = await optionService.GetOptionAsync(optionKey);
            var listFixture = Helper.ParseJsonToList<FixtureHomepage>(option?.OptionValue);
            var fixture = listFixture.Find(item => item.Id == id);
            var newListFixture = listFixture.Where(item => item.Id != id).ToList();
            var listDataRemove = HandleOrder(newListFixture, 0);
            var listFixtureJson = JsonConvert.SerializeObject(listDataRemove);
            var cmdResult = await optionService.UpdateOptionAsync(optionKey, listFixtureJson);
            return Json(cmdResult);
        }

        public IActionResult FootBall()
        {
            return View("~/Areas/Admin/Views/HomepageManagement/FootballNews/Index.cshtml");
        }

        public async Task<ActionResult> GetDataFootBallGrid()
        {
            var optionLeft = await optionService.GetOptionAsync(OptionKey.FootballNewsLeft);
            var optionCenter = await optionService.GetOptionAsync(OptionKey.FootballNewsCenter);
            var optionRight = await optionService.GetOptionAsync(OptionKey.FootballNewsRight);

            var FootballNews = new List<FootballNewsHomePage>{
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionLeft.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionCenter.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionRight.OptionValue)
            };

            return PartialView("~/Areas/Admin/Views/HomepageManagement/FootballNews/_FootballNewsDataGrid.cshtml", FootballNews);
        }

        [HttpGet]
        public async Task<ActionResult> EditCategory(int count)
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);

            var optionLeft = await optionService.GetOptionAsync(OptionKey.FootballNewsLeft);
            var optionCenter = await optionService.GetOptionAsync(OptionKey.FootballNewsCenter);
            var optionRight = await optionService.GetOptionAsync(OptionKey.FootballNewsRight);

            var footballNews = new List<FootballNewsHomePage>{
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionLeft.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionCenter.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionRight.OptionValue)
            };
            ViewBag.SlugSelected = footballNews[count - 1].Slug;
            ViewBag.Count = count;
            return PartialView("~/Areas/Admin/Views/HomepageManagement/FootballNews/_EditFootBall.cshtml", menus);
        }

        [HttpPost]
        public async Task<ActionResult> EditCategory(string category, int count)
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);
            var slug = menus.Where(x => x.Slug == category).ToList();
            var cateParents = await postService.GetCateParentsAsync(slug.First().Slug);
            var categoryUpdate = new FootballNewsHomePage
            {
                Category = slug.First().MenuTitle,
                Slug = slug.First().Slug,
                CreatedDate = DateTime.UtcNow,
                Url = PostHelper.GetCategoryLink(cateParents)

            };
            categoryUpdate.Url = categoryUpdate.Url[1..];
            var cmdResult = await optionService.UpdateOptionAsync(GetKeyOption(count), JsonConvert.SerializeObject(categoryUpdate));
            return Json(cmdResult);
        }

        public async Task<ActionResult> GetSlugs(string category)
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);
            var slug = menus.Where(x => x.Slug == category).ToList();
            return Json(slug[0]);
        }

        private static CateInfo GetCateInfo(string cateName, string cateSlug)
        {
            var slug = cateSlug;
            var name = cateName;
            if (cateSlug == Constants.CollectionSlug || (cateSlug.Contains(Constants.BasketballSlug)
                && !cateSlug.Contains(Constants.BasketballVideoSlug)) || cateSlug.Contains(Constants.TennisSlug))
            {
                return new CateInfo { Slug = Constants.OtherPostSlug, Name = Constants.OtherPosts };
            }

            var listCateSlug = cateSlug.Split(',');
            var listCateName = cateName.Split(',');
            var subCateSlug = listCateSlug.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
            var subCateName = listCateName.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
            slug = subCateSlug.Count > 0 ? subCateSlug[0] : listCateSlug[0];
            name = subCateName.Count > 0 ? subCateName[0] : listCateName[0];
            return new CateInfo { Slug = slug.Trim(), Name = name.Trim() };
        }

        public string GetKeyOption(int count)
        {
            string key;
            if (count == 1)
            {
                key = OptionKey.FootballNewsLeft;
            }
            else if (count == 2)
            {
                key = OptionKey.FootballNewsCenter;
            }
            else
            {
                key = OptionKey.FootballNewsRight;
            }
            return key;
        }

        private static string GetKeyOption(bool isFixtureOption)
        {
            return isFixtureOption ? OptionKey.HompageFixture : OptionKey.HompageStanding;
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