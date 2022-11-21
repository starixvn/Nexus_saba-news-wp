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
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;
    using System;
    using System.Collections.Generic;
    using System.Drawing.Printing;
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

        public async Task<ActionResult> ExportExcel()
        {
            var criteria = new GetDataMinigameCriteria
            {
                Start = Constants.FromFirstItem,
                End = Constants.Total,
                ToDate = DateTimeUtilize.TransformDatetimeToGMT(new DateTime(2022, 12, 18))
            };
            var miniGames = await leagueService.GetDataMinigameAsync(criteria);
            var bytes = excelService.ExportMinigameWorldCup2022(miniGames);
            return File(bytes, "application/xlsx", "MiniGameWorldCup2022.xlsx");
        }
    }
}