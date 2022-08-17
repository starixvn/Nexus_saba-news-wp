namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.User;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class TopScorerManagementController : AdminController
    {
        private readonly ILeagueService leagueService;
        public TopScorerManagementController(
            ILeagueService leagueService)
        {
            this.leagueService = leagueService;
        }
        public IActionResult Index()
        {
            return View();
        }

        public async Task<ActionResult> GetTopScorersData(string search)
        {
            var topScorers = await leagueService.GetAllTopScorersAsync();

            if(search != null) { 
                topScorers = topScorers.Where(x => (x.PlayerNameEN != null && x.PlayerNameEN.Contains(search))
                || (x.PlayerFullNameEN != null && x.PlayerFullNameEN.Contains(search))).ToList();
            }

            return PartialView("_TopScorersManagementDataGrid", topScorers);
        }

        [HttpGet]
        public async Task<ActionResult> AddPlayer()
        {
            var topScorers = await leagueService.GetAllTopScorersAsync();
            var teams = await leagueService.GetAllTeamAsync();
            var player = await leagueService.GetPlayerByIdTeamAsync(teams[0].TeamId);
            if (topScorers.Any()) {
                ViewBag.TopTotal = topScorers[topScorers.Count - 1].Top + 1;
            }
            else {
                ViewBag.TopTotal = 1;
            }
            player = player.Where(x => !topScorers.Where(y => y.PlayerId == x.PlayerId).Any()).ToList();
            var model = new PlayerViewModel {
                Teams = teams,
                Players = player
            };
            return PartialView("_AddPlayerDataGrid", model);
        }

        [HttpPost]
        public async Task<ActionResult> AddPlayer(TopScorers topScorers)
        {
            var result = await leagueService.InsertTopScorerAsync(topScorers);
            return Json(result);
        }

        [HttpGet]
        public async Task<ActionResult> GetPlayerByTeamId(long teamId)
        {
            var player = await leagueService.GetPlayerByIdTeamAsync(teamId);
            var topScorers = await leagueService.GetAllTopScorersAsync();
            player = player.Where(x => !topScorers.Where(y => y.PlayerId == x.PlayerId).Any()).ToList();
            return Json(player);
        }

        [HttpGet]
        public async Task<ActionResult> UpdateTopScorers(int rowNum, int teamId)
        {
             var teams = await leagueService.GetAllTeamAsync();
             var players = await leagueService.GetPlayerByIdTeamAsync(teamId);
             var playUpdate = await leagueService.GetTopScorersByRowNumAsync(rowNum);
             
             var model = new PlayerViewModel
             {
                 TopScorers = playUpdate[0],
                 Teams = teams,
                 Players = players
             };
             return PartialView("_EditPlayer", model);
        }

        [HttpPost]
        public async Task<ActionResult> UpdateTopScorers(TopScorers topScorers)
        {
            var result = await leagueService.UpdateTopScorerAsync(topScorers);
            return Json(result);
        }
    }
}
