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
            //var incidents = new List<SoccerMatchCommentaryEvent>();
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 10, Position = 0, Time = 0, HomeScore = 0, AwayScore = 0, PlayerName = null });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 1, Position = 1, Time = 20, HomeScore = 1, AwayScore = 0, PlayerName = "Player home 1" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 1, Position = 2, Time = 30, HomeScore = 1, AwayScore = 1, PlayerName = "Player away 1" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 2, Position = 1, Time = 31, HomeScore = 1, AwayScore = 1, PlayerName = "Player home 2" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 3, Position = 1, Time = 34, HomeScore = 1, AwayScore = 1, PlayerName = "Player home 3" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 4, Position = 1, Time = 36, HomeScore = 1, AwayScore = 1, PlayerName = "Player home 4" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 4, Position = 2, Time = 37, HomeScore = 1, AwayScore = 1, PlayerName = "Player away 2" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 16, Position = 2, Time = 37, HomeScore = 1, AwayScore = 1, PlayerName = "Player away 2" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 11, Position = 0, Time = 45, HomeScore = 1, AwayScore = 1, PlayerName = "Player away 2" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 9, Position = 1, Time = 50, HomeScore = 1, AwayScore = 1, InPlayerName = "Player home 3", OutPlayerName = "Player home 4" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 15, Position = 2, Time = 55, HomeScore = 1, AwayScore = 1, PlayerName = "Player away 2" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 17, Position = 1, Time = 60, HomeScore = 1, AwayScore = 1, PlayerName = "Player home 2" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 9, Position = 2, Time = 75, HomeScore = 1, AwayScore = 1, InPlayerName = "Player away 3", OutPlayerName = "Player away 4" });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 26, Position = 0, Time = 90, HomeScore = 1, AwayScore = 1 });
            //incidents.Add(new SoccerMatchCommentaryEvent { Type = 12, Position = 0, Time = 93, HomeScore = 1, AwayScore = 1 });
            //result.Commentary = incidents;
            //var result = new SoccerMatchDetail
            //{
            //    MatchId = matchId,
            //    LeagueId = 1,
            //    LeagueName = "La Liga",
            //    Stage = "",
            //    Round = 20,
            //    IsLineUp = 0,
            //    StartTime = new DateTime(2022, 03, 23, 19, 00, 00),
            //    EndTime = new DateTime(2022, 03, 23, 21, 00, 00),
            //    HomeTeamHalfScore = 0,
            //    AwayTeamHalfScore = 0,
            //    HomeTeamTotalScore = 0,
            //    AwayTeamTotalScore = 0,
            //    HomeTeamAggregateScore = 0,
            //    AwayTeamAggregateScore = 0,
            //    Status = 50,
            //    HomeTeam = new SoccerMatchTeamStatictis
            //    {
            //        TeamId = 1,
            //        NameEn = "Barcelona",
            //        Icon = "css/images/teams/Barcelona.png",
            //        RedCard = 2,
            //        YellowCard = 2,
            //        YellowToRed = 1
            //    },
            //    AwayTeam = new SoccerMatchTeamStatictis
            //    {
            //        TeamId = 2,
            //        NameEn = "Ath Bilbao",
            //        Icon = "css/images/teams/AthBilbao.png",
            //        RedCard = 3,
            //        YellowCard = 2,
            //        YellowToRed = 1
            //    }
            //};
            return Ok(result);
        }
    }
}