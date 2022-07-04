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

        public async Task<ActionResult> AddLeague(ManagementLeagueFilterModel model)
        {
            var leagues = await leagueService.GetLeaguesAsync();
            return PartialView("_AddLeagueDataGrid", leagues);
        }
    }
}
