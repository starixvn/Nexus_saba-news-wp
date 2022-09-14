namespace SabaNews.Site.Areas.Admin.Controllers.Livescore
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Match;
    using System.Linq;
    using SabaNews.Site.Extensions;
    using SabaNews.Core.Models;
    using System;
    using SabaNews.Services.Interfaces;
    using SabaNews.Models.Livescore.MatchManagement;
    using System.Globalization;

    public class MatchManagementController : AdminController
    {
        private readonly ILeagueService leagueService;
        private readonly IMatchService matchService;
        private readonly IFileService fileService;
        private const long DefaultLeagueId = WorldCup2022.LeagueIdWorldCup2022;
        private const long DefaultSeasonId = WorldCup2022.SeasonIdWorldCup2022;
        private const string SortDesc = "desc";
        private const string SortAsc = "asc";
        public MatchManagementController(
            ILeagueService leagueService,
            IMatchService matchService,
            IFileService fileService)
        {
            this.leagueService = leagueService;
            this.matchService = matchService;
            this.fileService = fileService;
        }
        public async Task<IActionResult> Index()
        {
            var leagues = await leagueService.GetLeaguesAsync();
            var model = new MatchManageModel
            {
                Leagues = leagues,
                LeagueId = DefaultLeagueId,
                SeasonId = DefaultSeasonId
            };
            
            return View("~/Areas/Admin/Views/Livescore/MatchManagement/Index.cshtml", model);
        }

        public async Task<IActionResult> GetSeason(int leagueId)
        {
            var seasons = await leagueService.GetSeasonByLeagueIdAsync(leagueId);
            return PartialView("~/Areas/Admin/Views/Livescore/MatchManagement/_SeasonFilter.cshtml", seasons);
        }

        [HttpPost]
        public async Task<IActionResult> GetMatchGrid(MatchFilterModel model)
        {
            var criteria = new GetMatchBySeasonFilterCriteria { LeagueId = model.LeagueId, SeasonId = model.SeasonId };
            var matches = await matchService.GetMatchBySeasonFilterAsync(criteria);
            matches = SearchMatch(matches, model.Search);
            matches = SortMatch(matches, model.DateSort);
            var totalItem = matches.Count;
            if (matches.Any())
            {
                matches = matches.Skip(model.PageSize * (model.Page - 1)).Take(model.PageSize).ToList();
            }
            var viewModel = new MatchManageGridVewModel
            {
                Matches = matches,
                DateSort = model.DateSort,
                Paging = new PagingModel
                {
                    CurrentPage = model.Page,
                    PageSize = model.PageSize,
                    TotalPages = (int)Math.Ceiling(totalItem / (double)model.PageSize),
                }
            };
            return PartialView("~/Areas/Admin/Views/Livescore/MatchManagement/_MatchGrid.cshtml", viewModel);
        }


        [HttpGet]
        public async Task<ActionResult> EditMatch(long matchId)
        {
            var match = await matchService.GetMatchByIdAsync(new GetMatchByIdCriteria { MatchId = matchId });
            return PartialView("~/Areas/Admin/Views/Livescore/MatchManagement/_Edit.cshtml", match);
        }

        [HttpPost]
        public async Task<ActionResult> EditMatch(MatchUpdateModel model)
        {
            var match = await matchService.GetMatchByIdAsync(new GetMatchByIdCriteria { MatchId = model.MatchId });
            var homeIconVN = model.IsDeletedHomeIcon ? "" : match.HomeTeam.IconVN;
            var awayIconVN = model.IsDeletedAwayIcon ? "" : match.AwayTeam.IconVN;
            if (model.HomeIcon != null)
            {
                var uploadResult = await fileService.UploadFileAsync(model.HomeIcon, UploadDir.MatchTeamUploadFolder);
                homeIconVN = uploadResult.IsSuccessful ? uploadResult.Message : homeIconVN;
            }
            if (model.AwayIcon != null)
            {
                var uploadResult = await fileService.UploadFileAsync(model.AwayIcon, UploadDir.MatchTeamUploadFolder);
                awayIconVN = uploadResult.IsSuccessful ? uploadResult.Message : awayIconVN;
            }
            var cmd = new UpdateMatchCommand
            {
                HomeTeamId = model.HomeTeamId,
                HomeTeamNameVN = model.HomeTeamNameVN,
                HomeTeamIconVN = homeIconVN,
                AwayTeamId = model.AwayTeamId,
                AwayTeamNameVN = model.AwayTeamNameVN,
                AwayTeamIconVN = awayIconVN
            };
            var result = await matchService.UpdateMatchAsync(cmd);
            
            return Json(result);
        }

        private static IList<Match> SearchMatch(IList<Match> matches, string search)
        {
            search ??= "";
            matches = matches.Where(x => (LivescoreHelper.GetTeamNameEN(x.HomeTeam).ToLower(CultureInfo.InvariantCulture).Contains(search))
                || (LivescoreHelper.GetTeamNameEN(x.AwayTeam).ToLower(CultureInfo.InvariantCulture).Contains(search))).ToList();
            return matches;
        }

        public IList<Match> SortMatch(IList<Match> matches, string typeSort)
        {
            if (typeSort == SortDesc)
            {
                matches = matches.OrderByDescending(x => x.StartTime).ToList();
            }
            if (typeSort == SortAsc)
            {
                matches = matches.OrderBy(x => x.StartTime).ToList();
            }
            return matches;
        }
    }
}
