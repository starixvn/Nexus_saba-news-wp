namespace SabaNews.Site.Areas.Admin.Controllers.Livescore
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.ManagementLeague;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;

    public class ManagementLeagueController : AdminController
    {
        private readonly IFileService fileService;
        private readonly ILeagueService leagueService;
        public ManagementLeagueController(
            ILeagueService leagueService, IFileService fileService)
        {
            this.leagueService = leagueService;
            this.fileService = fileService;
        }
        public async Task<ActionResult> Index()
        {
            IList<League> leagues = await leagueService.GetLeaguesAsync();
            leagues = leagueService.SearchLeague(leagues, null, 1);
            return View("~/Areas/Admin/Views/Livescore/ManagementLeague/Index.cshtml", leagues);
        }

        public async Task<ActionResult> GetManagementLeagueData(ManagementLeagueFilterModel model)
        {
            IList<League> leagues = await leagueService.GetLeaguesAsync();
            leagues = leagueService.SearchLeague(leagues, model.Search, model.IsMajor);
            leagues = leagueService.SortLeague(leagues, model.TypeSort);
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
            return PartialView("~/Areas/Admin/Views/Livescore/ManagementLeague/_ManagementLeagueDataGrid.cshtml", viewModel);
        }

        [HttpGet]
        public async Task<ActionResult> EditLeague(long leagueId)
        {
            IList<League> league = await leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = ("["+leagueId.ToString()+"]")});
            var leaguesExtra = await leagueService.GetLeaguesAsync();
            ViewBag.priority = LeagueService.GetLeaguePriority(leaguesExtra);
            return PartialView("~/Areas/Admin/Views/Livescore/ManagementLeague/_Edit.cshtml", league);
        }

        [HttpPost]
        public async Task<ActionResult> EditLeague(LeagueUpdateViewModel leagueUpdate)
        {
            IList<League> league = await leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = ("[" + leagueUpdate.LeagueId.ToString() + "]") });
            var icon = leagueUpdate.IsDeletedIcon ? null : league[0].CountryIconVN;
            if (leagueUpdate.Icon != null)
            {
                var uploadResult = await fileService.UploadFileAsync(leagueUpdate.Icon, UploadDir.LeagueUploadFolder);
                icon = uploadResult.IsSuccessful ? uploadResult.Message : icon;
            }
            var resultUpdateLeague = await leagueService.UpdateLeaguesAsync(new UpdateLeagueCriteria
            {
                LeagueId = leagueUpdate.LeagueId,
                LeagueNameVN = leagueUpdate.LeagueNameVN,
                CountryVN = leagueUpdate.CountryVN?.ToUpper(CultureInfo.InvariantCulture),
                CountryIconVN = icon
            });
            _ = await leagueService.UpdateLeaguesExtraAsync(new League
            {
                LeagueId = leagueUpdate.LeagueId,
                IsMajor = leagueUpdate.IsMajor,
                Priority = leagueUpdate.Priority
            });
            return Json(resultUpdateLeague);
        }

        public async Task<ActionResult> GetAllLeagues()
        {
            var leagues = await leagueService.GetAllLeaguesAsync();
            var leaguesExtra = await leagueService.GetLeaguesAsync();
            ViewBag.priority = LeagueService.GetLeaguePriority(leaguesExtra);
            return PartialView("~/Areas/Admin/Views/Livescore/ManagementLeague/_AddLeagueDataGrid.cshtml", leagues);
        }

        public async Task<ActionResult> GetLeagueById(long leagueId)
        {
            League league = await leagueService.GetLeagueByIdAsync(leagueId);
            return Json(league);
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

        public async Task<ActionResult> AddLeagueExtra(LeagueUpdateViewModel leagueUpdate)
        {
            var result = await leagueService.InsertLeaguesExtraAsync(leagueUpdate.LeagueId);
            _ = await leagueService.UpdateLeaguesAsync(new UpdateLeagueCriteria
            {
                LeagueId = leagueUpdate.LeagueId,
                LeagueNameVN = leagueUpdate.LeagueNameVN,
                CountryVN = leagueUpdate.CountryVN?.ToUpper(CultureInfo.InvariantCulture)
            });
            _ = await leagueService.UpdateLeaguesExtraAsync(new League
            {
                LeagueId = leagueUpdate.LeagueId,
                IsMajor = leagueUpdate.IsMajor,
                Priority = leagueUpdate.Priority
            });
            return Json(result);
        }
    }
}
