namespace SabaNews.Site.Controllers.Livescore
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Options;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Extensions;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;

    public class SoccerController : Controller
    {
        private readonly LivescoreMainPageCacheConfig cacheConfigs;
        private readonly ICacheWrapperService cacheService;
        private readonly IPostService postService;
        private readonly ILeagueService leagueService;
        private readonly IMatchService matchService;

        public SoccerController(
            IOptionsSnapshot<LivescoreMainPageCacheConfig> cacheConfigs,
            ICacheWrapperService cacheService,
            IPostService postService,
            ILeagueService leagueService,
            IMatchService matchService)
        {
            this.cacheConfigs = cacheConfigs.Value;
            this.cacheService = cacheService;
            this.postService = postService;
            this.leagueService = leagueService;
            this.matchService = matchService;
        }

        [Route("lich-thi-dau/bong-da/{eventDate?}")]
        public async Task<IActionResult> Index(DateTime? eventDate)
        {
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.MatchScheduleSlug);
            var title = pageDetail.SEOTitle ??= pageDetail.Title;
            var description = !string.IsNullOrWhiteSpace(pageDetail.Description) ? pageDetail.Description : title;
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
            var title = pageDetail.SEOTitle ??= pageDetail.Title;
            var description = !string.IsNullOrWhiteSpace(pageDetail.Description) ? pageDetail.Description : title;
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
            return View("~/Views/Livescore/Soccer/Detail.cshtml", matchId);
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

        [Route("lich-thi-dau/bong-da/giai-dau-quan-tam")]
        public IActionResult FavouriteLeague()
        {
            return View("~/Views/Livescore/Soccer/FavouriteLeague.cshtml");
        }

        [HttpPost]
        public async Task<IActionResult> GetMatches([FromBody] GetMatchByDateCriteria criteria)
        {
            criteria.FromTime = criteria.EventDate.ToStartOfDay().ConvertToUTC(criteria.Timezone);
            criteria.ToTime = criteria.EventDate.ToEndOfDay().ConvertToUTC(criteria.Timezone);
            criteria.SportId = (short)SportType.Soccer;
            IList<Match> matches;
            if (cacheConfigs.Enabled)
            {
                var cacheKey = string.Format("{0}{1}", CacheKey.LivescoreMainPage, criteria.EventDate.ToString("ddMMyyyy"));
                matches = await cacheService.GetAsync<IList<Match>>(cacheKey);
                if (matches is null)
                {
                    matches = await matchService.GetMatchByDateAsync(criteria);

                    var today = DateTime.UtcNow.Date;
                    byte timeout = 1;
                    if (criteria.EventDate.Date < today.AddDays(-cacheConfigs.DateRange)
                        || criteria.EventDate.Date > today.AddDays(cacheConfigs.DateRange))
                    {
                        timeout = cacheConfigs.OutOfRangeTimeout;
                    }

                    await cacheService.SetAsync(cacheKey, matches,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(timeout)
                        });
                }
            }
            else
            {
                matches = await matchService.GetMatchByDateAsync(criteria);
            }

            return Json(matches);
        }

        [HttpPost]
        public async Task<IActionResult> GetLiveMatches()
        {
            IList<Match> matches;
            if (cacheConfigs.Enabled)
            {
                matches = await cacheService.GetAsync<IList<Match>>(CacheKey.LivescoreLiveMatch);
                if (matches is null)
                {
                    matches = await matchService.GetLiveMatchAsync();
                    await cacheService.SetAsync(CacheKey.LivescoreLiveMatch, matches,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(1)
                        });
                }
            }
            else
            {
                matches = await matchService.GetLiveMatchAsync();
            }

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
                criteria.ToTime = model.Date.AddMonths(TotalMonths).ToEndOfDay().ConvertToUTC(model.Timezone);
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
            return Json(result);
        }

        [HttpPost]
        public async Task<IActionResult> GetMatchByIds([FromBody] GetMatchByIdsCriteria criteria)
        {
            var result = new MatchFavourite
            {
                Matches = await matchService.GetMatchByIdsAsync(criteria),
                LiveMatches = await matchService.GetLiveMatchAsync()
            };
            return Json(result);
        }

        [HttpPost]
        public async Task<IActionResult> GetFavLeagues([FromBody] FavLeagueModel model)
        {
            var leagues = model.LeagueIds == null
                        ? await leagueService.GetMajorLeagueAsync() 
                        : await leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = model.LeagueIds });
            return Ok(leagues);
        }

        [HttpPost]
        public async Task<IActionResult> GetLeagues()
        {
            var leagues = await leagueService.GetLeaguesAsync();
            return Ok(leagues);
        }

    }
}