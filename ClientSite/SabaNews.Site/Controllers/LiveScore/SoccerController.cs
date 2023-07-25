namespace SabaNews.Site.Controllers.Livescore
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.Options;
    using Newtonsoft.Json.Linq;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Extensions;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Models;
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
        private readonly IConfiguration configuration;
        private readonly string domain;

        public SoccerController(
            IOptionsSnapshot<LivescoreMainPageCacheConfig> cacheConfigs,
            ICacheWrapperService cacheService,
            IPostService postService,
            ILeagueService leagueService,
            IMatchService matchService,
            IConfiguration configuration)
        {
            this.cacheConfigs = cacheConfigs.Value;
            this.cacheService = cacheService;
            this.postService = postService;
            this.leagueService = leagueService;
            this.matchService = matchService;
            this.configuration = configuration;
            domain = configuration.GetValue<string>("BaseUrl");
        }

        [Route("lich-thi-dau/bong-da/{eventDate?}")]
        public IActionResult Index(DateTime? eventDate)
        {
            string title;
            string description;
            var today = DateTimeUtilize.ConvertToClientDatetime(DateTime.UtcNow.Date);
            if(eventDate == null || eventDate >= today)
            {
                title = "Lịch thi đấu bóng đá, trực tiếp bóng đá ngày " + today.ToString("dd/MM");
                description = $"Lịch thi đấu bóng đá ngày {today.ToString("dd/MM")} mới nhất. Cập nhật lịch thi đấu và trực tiếp bóng đá ngày {today.ToString("dd/MM")} liên tục trên SABA VN.";
            }
            else
            {
                title = "Kết quả bóng đá, tỷ số bóng đá ngày " + eventDate?.ToString("dd/MM");
                description = $"Xem kết quả bóng đá ngày {eventDate?.ToString("dd/MM")} chính xác nhất. Cập nhật tỉ số trận đấu ngày {eventDate?.ToString("dd/MM")} đầy đủ và trực quan nhất cho độc giả trân SABA VN.";
            }
            var pageDetail = new Category
            {
                Title = title,
                OgTitle = title,
                Description = description,
                OgDescription = description,
                Url = $"{domain}lich-thi-dau/bong-da"
            };
            var model = new PageViewModel
            {
                Category = pageDetail,
                Header = new HeaderViewModel
                {
                    CategorySlug = Constants.MatchScheduleSlug,
                    CategoryId = pageDetail.CateId,
                    Title = title,
                    Description = description
                }
            };
            ViewBag.EventDate = eventDate;

            return View("~/Views/Livescore/Soccer/Index.cshtml", model);
        }

        [Route("lich-thi-dau/bong-da/truc-tiep")]
        public IActionResult Live()
        {
            var url = new Category { Url = $"{domain}lich-thi-dau/bong-da/truc-tiep" };
            return View("~/Views/Livescore/Soccer/Live.cshtml", url);
        }

        [Route("lich-thi-dau/bong-da/{league}/{match}/{matchId:long}")]
        public IActionResult MatchDetail(string league, string match, long matchId)
        {
            ViewBag.Link = $"{domain}lich-thi-dau/bong-da/{league}/{match}/{matchId}";
            ViewBag.team = match;
            return View("~/Views/Livescore/Soccer/Detail.cshtml", matchId);
        }

        [Route("lich-thi-dau/bong-da/{league}/{seasonId:long}/{date:datetime}/{leagueId:long}/{selectedTab?}")]
        public async Task<IActionResult> LeagueDetail(string league, long seasonId, DateTime date, long leagueId, string selectedTab)
        {
            var tab = "tong-hop";
            var isRedirect = true;
            switch(selectedTab)
            {
                case null:
                case "general":
                    tab = "tong-hop";
                    break;
                case "fixtures":
                    tab = "lich-thi-dau";
                    break;
                case "results":
                    tab = "ket-qua";
                    break;
                case "standings":
                    tab = "bang-xep-hang";
                    break;
                default:
                    tab = "tong-hop";
                    isRedirect = false;
                    break;
            }
            if(isRedirect)
            {
                return Redirect($"/lich-thi-dau/bong-da/{league}/{seasonId}/{date.ToString("yyyy-MM-dd")}/{leagueId}/{tab}");
            }
            var leagueDetail = await leagueService.GetLeagueByIdAsync(leagueId);
            if(leagueDetail == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var model = new GetMatchBySeasonParams
            {
                LeagueId = leagueId,
                SeasonId = seasonId,
                Date = date,
                SelectedTab = selectedTab,
                LeagueName = league,
                League = leagueDetail
            };
            ViewBag.Link = $"{domain}lich-thi-dau/bong-da/{league}/{seasonId}/{date.ToString("yyyy-MM-dd")}/{leagueId}";
            return View("~/Views/Livescore/Soccer/LeagueDetail.cshtml", model);
        }

        [Route("lich-thi-dau/bong-da/tran-dau-quan-tam")]
        public IActionResult FavouriteMatch()
        {
            ViewBag.Link = $"{domain}lich-thi-dau/bong-da/tran-dau-quan-tam";
            return View("~/Views/Livescore/Soccer/FavouriteMatch.cshtml");
        }

        [Route("lich-thi-dau/bong-da/giai-dau-quan-tam")]
        public IActionResult FavouriteLeague()
        {
            ViewBag.Link = $"{domain}lich-thi-dau/bong-da/giai-dau-quan-tam";
            return View("~/Views/Livescore/Soccer/FavouriteLeague.cshtml");
        }

        [HttpPost]
        public async Task<IActionResult> GetMatches([FromBody] GetMatchByDateCriteria criteria)
        {
            criteria.FromTime = criteria.EventDate.ToStartOfDay().ConvertToUTC(criteria.Timezone);
            criteria.ToTime = criteria.EventDate.ToEndOfDay().ConvertToUTC(criteria.Timezone);
            criteria.SportId = (short)SportType.Soccer;
            IList<SabaNews.Livescore.Models.Match.Match> matches;
            if (cacheConfigs.Enabled)
            {
                var cacheKey = string.Format("{0}{1}", CacheKey.LivescoreMainPage, criteria.EventDate.ToString("ddMMyyyy"));
                matches = await cacheService.GetAsync<IList<SabaNews.Livescore.Models.Match.Match>>(cacheKey);
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
            IList<SabaNews.Livescore.Models.Match.Match> matches;
            if (cacheConfigs.Enabled)
            {
                matches = await cacheService.GetAsync<IList<SabaNews.Livescore.Models.Match.Match>>(CacheKey.LivescoreLiveMatch);
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
            if (model == null)
            {
                return Json(null);
            }
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
        public async Task<IActionResult> GetLeagueSeasonStanding([FromBody] GetLeagueAndSeasonStandingsCriteria criteria)
        {
            var (league, standing) = await leagueService.GetLeagueAndSeasonStandingAsync(criteria);
            return Json(new { league, standing });
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
            if (criteria == null)
            {
                return Json(null);
            }
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
        public async Task<IActionResult> GetFavMatches([FromBody] GetMatchByIdsCriteria criteria)
        {
            if (!ValidateJson(criteria.MatchIds))
            {
                return Json(null);
            }
            var result = new MatchFavourite
            {
                Matches = await matchService.GetMatchByIdsAsync(criteria),
                LiveMatches = await matchService.GetLiveMatchAsync()
            };
            return Json(result);
        }

        [HttpPost]
        public async Task<IActionResult> GetMatchByIds(string matchIds)
        {
            var result = await matchService.GetMatchByIdsAsync(new GetMatchByIdsCriteria { MatchIds = matchIds });
            return Json(result);
        }

        [HttpPost]
        public async Task<IActionResult> GetFavLeagues([FromBody] FavLeagueModel model)
        {
            if(model.LeagueIds != null && !ValidateJson(model.LeagueIds))
            {
                return Json(null);
            }
            var leagues = model.LeagueIds == null
                        ? await leagueService.GetMajorLeagueAsync()
                        : await leagueService.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = model.LeagueIds });
            return Json(leagues);
        }

        [HttpPost]
        public async Task<IActionResult> GetLeagues()
        {
            var leagues = await leagueService.GetLeaguesAsync();
            return Json(leagues);
        }

        [HttpPost]
        public async Task<IActionResult> GetMatchGeneralInfo([FromBody] GetMatchByIdCriteria criteria)
        {
            var match = await matchService.GetMatchByIdAsync(criteria);
            var incidents = await matchService.GetMatchIncidentAsync(new GetMatchIncidentCriteria { MatchId = criteria.MatchId });
            return Json(new { match, incidents });
        }

        private static bool ValidateJson(string json)
        {
            try
            {
                JToken.Parse(json);
                return true;
            }
            catch (Exception)
            {
                return false;
            }
        }

    }
}