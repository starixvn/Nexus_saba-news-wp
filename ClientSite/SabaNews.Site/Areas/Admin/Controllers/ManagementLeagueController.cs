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

        public async Task<ActionResult> GetManagementLeagueData(ManagementLeagueFilterModel model)
        {
            IList<League> leagues = await leagueService.GetLeaguesAsync();
            leagues = Searching(leagues, model.Search, model.IsMajor);
            leagues = Sort(leagues, model.TypeSort);
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

        [HttpGet]
        public ActionResult EditLeague()
        {            
            return PartialView("_Edit");
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

        public async Task<ActionResult> ChangeMajor(League league)
        {         
            var result = await leagueService.UpdateLeaguesExtraAsync(league);
            return Json(result);
        }

        public async Task<ActionResult> ChangePriority(League league)
        {
            var result = await leagueService.UpdateLeaguesExtraAsync(league);
            return Json(result);
        }

        public async Task<ActionResult> RemoveLeagueExtra(long leagueId)
        {
            var result = await leagueService.DeleteLeaguesExtraAsync(leagueId);
            return Json(result);
        }

        public async Task<ActionResult> AddLeagueExtra(long leagueId)
        {
            var result = await leagueService.InsertLeaguesExtraAsync(leagueId);
            return Json(result);
        }

        private static IList<League> Searching(IList<League> leagues, string search, int isMajor)
        {
            if (search != null){
                leagues = leagues.Where(x => (x.LeagueFullNameEN != null && x.LeagueFullNameEN.Contains(search))
                || (x.LeagueNameVN != null && x.LeagueNameVN.Contains(search))
                || (x.LeagueNameEN != null && x.LeagueNameEN.Contains(search))).ToList();
            }
            switch (isMajor)
            {
                case 1:
                    leagues = leagues.Where(x => x.IsMajor == true).ToList();
                    break;
                case 0:
                    leagues = leagues.Where(x => x.IsMajor == false).ToList();
                    break;                
            }
            return leagues;
        }

        private static IList<League> Sort(IList<League> leagues, int typeSort)
        {
            switch (typeSort)
            {
                case 1:
                    leagues = leagues.OrderBy(x => x.Priority).ToList();
                    break; 
                case 2:
                    leagues = leagues.OrderByDescending(x => x.Priority).ToList();
                    break;
                case 3:
                    leagues = leagues.OrderByDescending(x => x.CreatedDate).ToList();
                    break;
                case 4:
                    leagues = leagues.OrderBy(x => x.CreatedDate).ToList();
                    break;
            }
            return leagues;
        }
    }
}
