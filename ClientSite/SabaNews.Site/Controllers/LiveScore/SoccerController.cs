namespace SabaNews.Site.Controllers.Livescore
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Extensions;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;

    public class SoccerController : Controller
    {
        private readonly IPostService postService;
        private readonly ILeagueService leagueService;
        private readonly IMatchService matchService;

        public SoccerController(
            IPostService postService,
            ILeagueService leagueService,
            IMatchService matchService)
        {
            this.postService = postService;
            this.leagueService = leagueService;
            this.matchService = matchService;
        }

        [Route("lich-thi-dau/bong-da/{eventDate?}")]
        public async Task<IActionResult> Index(DateTime? eventDate)
        {
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.MatchScheduleSlug);
            var title = pageDetail.Title;
            var description = pageDetail.Description ??= title;
            var model = new PageViewModel
            {
                Category = pageDetail,
                Header = new HeaderViewModel
                {
                    CategorySlug = Constants.MatchScheduleSlug,
                    CategoryId = pageDetail.CatId,
                    Title = title,
                    Description = description
                }
            };
            ViewBag.EventDate = eventDate;

            return View("~/Views/Livescore/Soccer/Index.cshtml", model);
        }

        [Route("lich-thi-dau/bong-da/truc-tiep")]
        public async Task<IActionResult> Live()
        {
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.MatchScheduleSlug);
            var title = pageDetail.Title;
            var description = pageDetail.Description ??= title;
            var model = new PageViewModel
            {
                Category = pageDetail,
                Header = new HeaderViewModel
                {
                    CategorySlug = Constants.MatchScheduleSlug,
                    CategoryId = pageDetail.CatId,
                    Title = title,
                    Description = description
                }
            };
            return View("~/Views/Livescore/Soccer/Live.cshtml", model);
        }

        [Route("lich-thi-dau/bong-da/{league}/{match}/{matchId:long}")]
        public IActionResult MatchResult(string league, string match, long matchId)
        {
            return View("~/Views/LiveScore/Soccer/Detail.cshtml", matchId);
        }

        [Route("lich-thi-dau/bong-da/{league}/{seasonId:long}/{date:datetime}/{leagueId:long}/{selectedTab?}")]
        public IActionResult LeagueDetail(string league, long seasonId, DateTime date, long leagueId, string selectedTab)
        {
            var model = new GetMatchBySeasonParams
            {
                LeagueId = leagueId,
                SeasonId = seasonId,
                Date = date,
                SelectedTab = selectedTab
            };
            return View("~/Views/Livescore/Soccer/LeagueDetail.cshtml", model);
        }

        [Route("lich-thi-dau/bong-da/tran-dau-quan-tam")]
        public IActionResult FavouriteMatch()
        {
            return View("~/Views/Livescore/Soccer/FavouriteMatch.cshtml");
        }

        [HttpPost]
        public async Task<IActionResult> GetMatches([FromBody] GetMatchByDateCriteria criteria)
        {
            criteria.FromTime = criteria.EventDate.ToStartOfDay().ConvertToUTC(criteria.Timezone);
            criteria.ToTime = criteria.EventDate.ToEndOfDay().ConvertToUTC(criteria.Timezone);
            criteria.SportId = (short)SportType.Soccer;
            IList<Match> matches = await matchService.GetMatchByDateAsync(criteria);
            return Json(matches);
        }

        [HttpPost]
        public async Task<IActionResult> GetLiveMatches()
        {
            IList<Match> matches = await matchService.GetLiveMatchAsync();
            return Json(matches);
        }

        [HttpPost]
        public async Task<IActionResult> GetMatchesBySeason([FromBody] GetMatchBySeasonParams model)
        {
            var criteria = new GetMatchBySeasonCriteria
            {
                LeagueId = model.LeagueId,
                SeasonId = model.SeasonId
            };
            if (criteria.SeasonId == 0)
            {
                const byte TotalMonths = 6;
                criteria.FromTime = model.Date.AddMonths(-TotalMonths).ToStartOfDay().ConvertToUTC(model.Timezone);
                criteria.ToTime = model.Date.AddMonths(TotalMonths).ToStartOfDay().ConvertToUTC(model.Timezone);
            }

            (League league, IList<Match> matches) = await matchService.GetMatchBySeasonAsync(criteria);
            return Json(new { league, matches });
        }

        [HttpPost]
        public async Task<IActionResult> GetSeasonStandings([FromBody] GetSeasonStandingsCriteria criteria)
        {
            IList<SeasonStandings> standings = await matchService.GetSeasonStandingsAsync(criteria);
            return Json(standings);
        }

        [HttpPost]
        public async Task<IActionResult> GetMajorLeagues()
        {
            IList<League> leagues = await leagueService.GetMajorLeagueAsync();
            return Json(leagues);
        }


        [HttpPost]
        public async Task<IActionResult> GetMatchDetail([FromBody] GetMatchByIdCriteria criteria)
        {
            var matchId = criteria.MatchId;
            var result = new MatchDetail
            {
                GeneralInfo = await matchService.GetMatchByIdAsync(new GetMatchByIdCriteria { MatchId = matchId }),
                Statistic = await matchService.GetMatchStatisticAsync(new GetMatchStatisticCriteria { MatchId = matchId }),
                HeadToHead = await matchService.GetHeadToHeadAsync(new GetHeadToHeadCriteria { MatchId = matchId }),
                Lineup = await matchService.GetLineupAsync(new GetMatchLineupCriteria { MatchId = matchId }),
                Incidents = await matchService.GetMatchIncidentAsync(new GetMatchIncidentCriteria { MatchId = matchId })
            };

            return Ok(result);
        }

        [HttpPost]
        public async Task<IActionResult> GetMatchByIds([FromBody] GetMatchByIdsCriteria model)
        {
            var result = new MatchFavourite
            {
                Matches = await matchService.GetMatchByIdsAsync(model),
                LiveMatches = await matchService.GetLiveMatchAsync()
            };
            return Ok(result);
        }

    }
}