namespace SabaNews.Site.Areas.Admin.Controllers.Livescore
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Livescore.MatchManagement;
    using SabaNews.Models.Livescore.StandingManagement;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;
    using System.Threading.Tasks;

    public class StandingManagementController : AdminController
    {
        private readonly ILeagueService leagueService;
        private readonly IMatchService matchService;
        private readonly IFileService fileService;
        private const long DefaultLeagueId = WorldCup2022.LeagueIdWorldCup2022;
        private const long DefaultSeasonId = WorldCup2022.SeasonIdWorldCup2022;
       
        public StandingManagementController(
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

            return View("~/Areas/Admin/Views/Livescore/StandingManagement/Index.cshtml", model);
        }

        [HttpPost]
        public async Task<IActionResult> GetDataGrid(StandingFilterModel model)
        {
            var criteria = new GetLeagueSeasonStandingCriteria { SeasonId = model.SeasonId, LeagueId = model.LeagueId };
            var standings = await leagueService.GetLeagueSeasonStanding(criteria);
            var groupStanding = LivescoreHelper.GroupStandingsByGroupName(standings);
            var viewModel = new StandingManageGridViewModel
            {
                Groups = groupStanding,
                SeasonId = model.SeasonId
            };
            return PartialView("~/Areas/Admin/Views/Livescore/StandingManagement/_DataGrid.cshtml", viewModel);
        }

        [HttpGet]
        public async Task<ActionResult> EditStanding(long seasonId, long stageId, long teamId)
        {
            var criteria = new GetLeagueSeasonStandingDetailCriteria { SeasonId = seasonId, StageId = stageId, TeamId = teamId };
            var standings = await leagueService.GetLeagueSeasonStandingDetailAsync(criteria);
            return PartialView("~/Areas/Admin/Views/Livescore/StandingManagement/_Edit.cshtml", standings);
        }

        [HttpPost]
        public async Task<ActionResult> EditStanding(StandingUpdateModel model)
        {
            var criteria = new GetLeagueSeasonStandingDetailCriteria { SeasonId = model.SeasonId, StageId = model.StageId, TeamId = model.TeamId };
            var standings = await leagueService.GetLeagueSeasonStandingDetailAsync(criteria);
            var iconVN = model.IsDeletedIcon ? "" : standings.IconVN;
            if (model.Icon != null)
            {
                var uploadResult = await fileService.UploadFileAsync(model.Icon, UploadDir.MatchTeamUploadFolder);
                iconVN = uploadResult.IsSuccessful ? uploadResult.Message : iconVN;
            }
            model.TeamIconVN = iconVN;
            model.IsUpdatePosition = standings.Position != model.Position;
            
            var result = await leagueService.UpdateStandingAsync(model);

            return Json(result);
        }

    }
}
