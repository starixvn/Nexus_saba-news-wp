namespace SabaNews.Site.Controllers.Livescore
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Extensions;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class SoccerController : Controller
    {
        private readonly ILeagueService leagueService;
        private readonly IMatchService matchService;

        public SoccerController(
            ILeagueService leagueService,
            IMatchService matchService)
        {
            this.leagueService = leagueService;
            this.matchService = matchService;
        }

        [Route("lich-thi-dau/bong-da/{eventDate?}")]
        public IActionResult Index(DateTime? eventDate)
        {
            var today = DateTime.Today;
            var minDate = new DateTime(2020, 1, 1);
            var maxDate = new DateTime(today.Year + 2, 1, 1).AddYears(1).AddDays(-1);
            eventDate = eventDate.HasValue ? eventDate.Value.Date : DateTime.Today;

            if(eventDate.Value < minDate.Date)
            {
                eventDate = minDate;
            }
            else
            {
                if(eventDate.Value > maxDate)
                {
                    eventDate = maxDate;
                }
            }

            return View("~/Views/Livescore/Soccer/Index.cshtml", eventDate);
        }

        [Route("lich-thi-dau/bong-da/truc-tiep")]
        public IActionResult Live()
        {
            return View("~/Views/Livescore/Soccer/Live.cshtml", DateTime.Today);
        }

        [Route("lich-thi-dau/bong-da/detail/{matchId:long}")]
        public IActionResult Detail(long matchId)
        {
            return View("~/Views/LiveScore/Soccer/Detail.cshtml", matchId);
        }

        [HttpPost]
        public async Task<IActionResult> GetMatches([FromBody] GetMatchByEventDateCriteria criteria)
        {
            criteria.FromTime = criteria.EventDate.ToStartOfDay().ConvertToUTC();
            criteria.ToTime = criteria.EventDate.ToEndOfDay().ConvertToUTC();
            criteria.SportId = (short)SportType.Soccer;
            IEnumerable<Match> matches = await matchService.GetMatchByEventDateAsync(criteria);
            return Json(matches);
        }

        [HttpPost]
        public async Task<IActionResult> GetMajorLeagues()
        {
            IEnumerable<League> leagues = await leagueService.GetMajorLeagueAsync();
            return Json(leagues);
        }

        [HttpPost]
        public async Task<IActionResult> GetLineupDetail([FromBody] GetMatchLineupCriteria criteria)
        {
            var lineup = await matchService.GetLineupAsync(criteria);
            return Ok(lineup);
        }

        [HttpPost]
        public async Task<IActionResult> GetSoccerMatchDetail([FromBody] GetMatchDetailCriteria criteria)
        {
            var generalInfo = await matchService.GetMatchDetailAsync(criteria);
            var postMatchInfo = await matchService.GetMatchDetailPostMatch(new GetMatchDetailPostMatchCriteria { MatchId = criteria.MatchId });
            var result = new MatchDetail
            {
                GeneralInfo = generalInfo,
                AwayTeam = postMatchInfo.AwayStatistic,
                HomeTeam = postMatchInfo.HomeStatistic,
                Incidents = postMatchInfo.Incidents,
            };
            return Ok(result);
        }
    }
}