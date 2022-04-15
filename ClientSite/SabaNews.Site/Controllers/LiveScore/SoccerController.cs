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
    using SabaNews.Livescore.Services.Interfaces;
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

        [Route("lich-thi-dau/bong-da/chi-tiet/{matchId:long}")]
        public IActionResult Detail(long matchId)
        {
            return View("~/Views/LiveScore/Soccer/Detail.cshtml", matchId);
        }

        [Route("lich-thi-dau/bong-da/chi-tiet-giai-dau/{date:datetime}/{leagueId:long}/{seasonId:long}")]
        public IActionResult LeagueDetail(DateTime date, long leagueId, long seasonId)
        {
            var model = new GetMatchBySeasonParams
            {
                LeagueId = leagueId,
                SeasonId = seasonId,
                Date = date
            };
            return View("~/Views/Livescore/Soccer/LeagueDetail.cshtml", model);
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
                SportId = (short)SportType.Soccer,
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
        public async Task<IActionResult> GetMajorLeagues()
        {
            IList<League> leagues = await leagueService.GetMajorLeagueAsync();
            return Json(leagues);
        }

        [HttpPost]
        public async Task<IActionResult> GetLineupDetail([FromBody] GetMatchLineupCriteria criteria)
        {
            var lineup = await matchService.GetLineupAsync(criteria);
            return Ok(lineup);
        }

        [HttpPost]
        public async Task<IActionResult> GetHeadToHead([FromBody] GetHeadToHeadCriteria criteria)
        {
            var headToHead = await matchService.GetHeadToHeadAsync(criteria);
            return Ok(headToHead);
        }

        [HttpPost]
        public async Task<IActionResult> UpdateDetailData([FromBody] GetMatchDetailCriteria criteria)
        {
            var matchId = criteria.MatchId;
            var result = await matchService.GetMatchDetailAsync(criteria);
            return Ok();
        }

        [HttpPost]
        public async Task<IActionResult> GetMatchDetail([FromBody] GetMatchDetailCriteria criteria)
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
    }
}