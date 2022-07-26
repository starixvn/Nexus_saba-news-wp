namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.ManagementLeague;
    using SabaNews.Models.ViewModel.User;
    using System;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;

    public class ManagementLeagueController : AdminController
    {
        private readonly ILeagueService leagueService;
        private const int priorityAscending = 1;
        private const int priorityDescending = 2;
        private const int createdDateDescending = 3;
        private const int createdDateAscending = 4;
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
        public async Task<ActionResult> EditLeague(long leagueId)
        {
            IList<League> league = await leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = ("["+leagueId.ToString()+"]")});
            
            return PartialView("_Edit", league);
        }

        [HttpPost]
        public async Task<ActionResult> EditLeague(LeagueUpdateViewModel leagueUpdate)
        {
            var resultUpdateLeague = await leagueService.UpdateLeaguesAsync(new UpdateLeagueCriteria
            {
                LeagueId = leagueUpdate.LeagueId,
                LeagueNameVN = leagueUpdate.LeagueNameVN,
                Country = leagueUpdate.Country,
                CountryVN = leagueUpdate.CountryVN.ToUpper(CultureInfo.InvariantCulture)
            });
            _ = await leagueService.UpdateLeaguesExtraAsync(new League
            {
                LeagueId = leagueUpdate.LeagueId,
                IsMajor = leagueUpdate.IsMajor,
                Priority = leagueUpdate.Priority
            });
            return Json(resultUpdateLeague);
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

        public async Task<ActionResult> UpdateLeagueExtra(League league)
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
                || (x.LeagueNameVN != null && x.LeagueNameVN.Contains(search))).ToList();
            }
            if(isMajor == 1){
                leagues = leagues.Where(x => x.IsMajor).ToList();
            }
            if (isMajor == 0)
            {
                leagues = leagues.Where(x => !x.IsMajor).ToList();
            }            
            return leagues;
        }

        private static IList<League> Sort(IList<League> leagues, int typeSort)
        {
            if (typeSort == priorityAscending) { 
                leagues = leagues.OrderBy(x => x.Priority).ToList();
            }
            if (typeSort == priorityDescending)
            {
                leagues = leagues.OrderByDescending(x => x.Priority).ToList();
            }
            if (typeSort == createdDateDescending)
            {
                leagues = leagues.OrderByDescending(x => x.CreatedDate).ToList();
            }
            if (typeSort == createdDateAscending)
            {
                leagues = leagues.OrderBy(x => x.CreatedDate).ToList();
            }
            return leagues;
        }
    }
}
