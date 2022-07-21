namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Models.ViewModel.User;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class ManagementLeagueController : AdminController
    {
        private readonly ILeagueService leagueService;

        public ManagementLeagueController(
            ILeagueService leagueService)
        {
            this.leagueService = leagueService;
        }
        public IActionResult Index()
        {
            return View();
        }
        public IActionResult SearchData(string searchLeague, int status)
        {
            ViewBag.SearchLeague = searchLeague;
            ViewBag.IsMajor = status;
            return View("Index");
        }

        public async Task<ActionResult> GetManagementLeagueData(ManagementLeagueFilterModel model)
        {
            IList<League> leagues = await leagueService.GetLeaguesAsync();
            leagues = Searching(leagues, model.Search, model.IsMajor);
            var totalLeague = leagues.Count;
            if (leagues.Count > 0)
            {
                leagues = leagues.Skip(model.PageSize * (model.Page - 1)).Take(model.PageSize).ToList();
            }
            var viewModel = new ManagementLeagueGridViewModel
            {
                Leagues = leagues,
                Paging = new PagingModel
                {
                    CurrentPage = model.Page,
                    PageSize = model.PageSize,
                    TotalPages = (int)Math.Ceiling(totalLeague / (double)model.PageSize),
                }
            };
            return PartialView("_ManagementLeagueDataGrid", viewModel);
        }

        public async Task<ActionResult> GetAllLeagues(ManagementAddLeagueFilterModel model)
        {
            var leagues = await leagueService.GetAllLeaguesAsync();
            leagues = Searching(leagues, model.SearchAddLeague, -1);
            var totalLeague = leagues.Count;
            if (leagues.Count > 0)
            {
                leagues = leagues.Skip(model.PageSizeAddLeague * (model.PageAddLeague - 1)).Take(model.PageSizeAddLeague).ToList();
            }
            var viewModel = new ManagementLeagueGridViewModel
            {
                Leagues = leagues,
                Paging = new PagingModel
                {
                    CurrentPage = model.PageAddLeague,
                    PageSize = model.PageSizeAddLeague,
                    TotalPages = (int)Math.Ceiling(totalLeague / (double)model.PageSizeAddLeague),
                }
            };
            ViewBag.SearchAddLeague = model.SearchAddLeague;
            return PartialView("_AddLeagueDataGrid", viewModel);
        }

        [HttpPost]
        public async Task<ActionResult> ChangeMajor(int leagueId, int priority, bool isMajor)
        {         
           var result = await leagueService.UpdateLeaguesExtraAsync(leagueId, priority, isMajor);
           return Json(new { result });
        }

        [HttpPost]
        public async Task<ActionResult> ChangePriority(int leagueId, int priority, bool isMajor)
        {
            var result = await leagueService.UpdateLeaguesExtraAsync(leagueId, priority, isMajor);
            return Json(new { result });
        }

        [HttpPost]
        public async Task<ActionResult> RemoveLeagueExtra(int leagueId)
        {
            var result = await leagueService.DeleteLeaguesExtraAsync(leagueId);
            return Json(new { result });
        }

        [HttpPost]
        public async Task<ActionResult> AddLeagueExtra(int leagueId)
        {
            var result = await leagueService.InsertLeaguesExtraAsync(leagueId);
            return Json(new { result });
        }

        private static IList<League> Searching(IList<League> leagues, string search, int isMajor)
        {
            if (search != null)
            {
                leagues = leagues.Where(x => x.LeagueFullNameEN.Contains(search)).ToList();
            }
            if (isMajor == 1)
            {
                leagues = leagues.Where(x => x.IsMajor == true).ToList();
            }
            if (isMajor == 0)
            {
                leagues = leagues.Where(x => x.IsMajor == false).ToList();
            }
            return leagues;
        }
    }
}
