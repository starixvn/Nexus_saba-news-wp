namespace SabaNews.Site.Areas.Admin.Controllers
{
    using LiteDB;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;
    using System;
    using System.Collections.Generic;
    using System.Drawing.Printing;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;

    public class MinigameWorldCup2022Controller : AdminController
    {
        private readonly ILeagueService leagueService;
        private readonly IExportExcelService excelService;
        private readonly IWorldCupService worldcupService;
        public MinigameWorldCup2022Controller(
            IExportExcelService excelService, 
            ILeagueService leagueService,
            IWorldCupService worldcupService)
        {
            this.excelService = excelService;
            this.leagueService = leagueService;
            this.worldcupService = worldcupService;
        }
        public IActionResult MinigameWorldCup2022()
        {
            return View();
        }

        public IActionResult CountUser()
        {
            return View();
        }

        public async Task<ActionResult> GetDataMinigameWorldCup2022(UserFilterMembershipEventModel model)
        {
            var criteria = new GetMinigameUsersByStageCriteria
            {
                stage = model.Stage
            };
            var criteriaMatch = new GetMatchBySeasonCriteria
            {
                LeagueId = WorldCup2022.LeagueIdWorldCup2022,
                SeasonId = WorldCup2022.SeasonIdWorldCup2022
            };
            var miniGames = await worldcupService.GetMinigameUsersAsync(criteria);
            IList<Match> matches = await worldcupService.GetMatchBySeasonAsync(criteriaMatch);
            miniGames = LivescoreHelper.CalculatorPointer(matches, miniGames);
            var miniGamesPaging = miniGames.Skip(model.PageSize * (model.Page - 1)).Take(model.PageSize).ToList();
            var viewModel = new MinigameGridViewModel
            {
                MiniGames = miniGamesPaging,
                Paging = new PagingModel
                {
                    CurrentPage = model.Page,
                    PageSize = model.PageSize,
                    TotalPages = (int)Math.Ceiling(miniGames.Count / (double)model.PageSize),
                }
            };
            return PartialView("_MinigameWorldCup2022DataGrid", viewModel);
        }

        public async Task<ActionResult> GetCountUserGrid(string dateSearch)
        {
            List<string> startStages = new() {
                WorldCup2022.TimestampMinigameStage01Start,
                WorldCup2022.TimestampMinigameStage02Start,
                WorldCup2022.TimestampMinigameStage03Start,
                WorldCup2022.TimestampMinigameStage04Start };
            var search = DateTime.Parse(dateSearch);
            var miniGames = await worldcupService.GetMinigameAllUsersAsync();
            var users = new List<UserMinigameModel>();
            
                users = miniGames.Where(item => item.CreatedDate.AddHours(7).Date == search.Date
                                            || item.UpdatedDate.AddHours(7).Date == search.Date).ToList();
            
            var tmpUsers = new List<UserMinigameModel>();
            foreach (var user in users)
            {
                var isExists = tmpUsers.Find(value => value.UserId == user.UserId);
                if (isExists == null)
                {
                    tmpUsers.Add(user);
                }
            }

            return PartialView("_CountUserGrid", tmpUsers);
        }

        public IActionResult GetDetailUser(byte stageDetail, long userId)
        {
            return Ok();
        }

        public async Task<ActionResult> ExportExcel(byte stage)
        {
            var criteria = new GetMinigameUsersByStageCriteria
            {
                stage = stage
            };
            var criteriaMatch = new GetMatchBySeasonCriteria
            {
                LeagueId = WorldCup2022.LeagueIdWorldCup2022,
                SeasonId = WorldCup2022.SeasonIdWorldCup2022
            };
            var miniGames = await worldcupService.GetMinigameUsersAsync(criteria);
            IList<Match> matches = await worldcupService.GetMatchBySeasonAsync(criteriaMatch);
            miniGames = LivescoreHelper.CalculatorPointer(matches, miniGames);
            var bytes = excelService.ExportMinigameWorldCup2022(miniGames);
            return File(bytes, "application/xlsx", "MiniGameWorldCup2022.xlsx");
        }
    }
}