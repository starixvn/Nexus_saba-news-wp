namespace SabaNews.Site.Controllers.Event
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Posts;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class WorldCup2022Controller : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;
        private readonly IMatchService matchService;
        private readonly ILeagueService leagueService;
        private const long LeagueIdWC = WorldCup2022.LeagueIdWorldCup2022;
        private const long SeasonIdWC = WorldCup2022.SeasonIdWorldCup2022;
        private const string CategorySlugWC = WorldCup2022.HomepageSlug;

        public WorldCup2022Controller(
            IPostService postService,
            IDataControlService dataControlService,
            IMatchService matchService,
            ILeagueService leagueService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
            this.matchService = matchService;
            this.leagueService = leagueService;
        }

        [Route("world-cup-2022")]
        public async Task<IActionResult> Index()
        {
            var model = await dataControlService.GetWorldCupPageViewModel();
            var title = model.Category.SEOTitle ??= model.Category.Title;

            var header = new HeaderViewModel
            {
                HeadTitle = model.Category.Title,
                Title = title,
                Description = model.Category.Description,
                Slug = WorldCup2022.HomepageSlug
            };
            model.Header = header;
            return View("~/Views/Event/WorldCup2022/Index.cshtml", model);
        }

        [Route("world-cup-2022/tin-tuc-world-cup-2022")]
        public async Task<IActionResult> LastestNews()
        {
            var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = CategorySlugWC, _Start = 0, _End = Constants.GetTenItems};
            var news = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);

            var header = new HeaderViewModel
            {
                Title = WorldCup2022.LastestNews,
                Slug = WorldCup2022.LastestNewsSlug
            };

            var viewModel = new LastestNewsViewModel
            {
                Header =header,
                Posts = news,
                Paging = new PagingModel{
                    PageSize = Constants.GetTenItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)Constants.GetTenItems)
                }
            };
            ViewBag.Slug = CategorySlugWC;
            return View("~/Views/Event/WorldCup2022/LastestNewsWC.cshtml", viewModel);
        }

        [Route("world-cup-2022/nhan-dinh-world-cup-2022")]
        public async Task<IActionResult> CommentaryNews()
        {
            var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = WorldCup2022.CommentarySlug, _Start = 0, _End = Constants.GetTenItems };
            var news = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);

            var header = new HeaderViewModel
            {
                Title = WorldCup2022.Commentary,
                Slug = WorldCup2022.CommentarySlug
            };

            var viewModel = new LastestNewsViewModel
            {
                Header = header,
                Posts = news,
                Paging = new PagingModel
                {
                    PageSize = Constants.GetTenItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)Constants.GetTenItems)
                }
            };
            return View("~/Views/Event/WorldCup2022/Commentary.cshtml", viewModel);
        }

        [Route("world-cup-2022/lich-thi-dau-world-cup-2022")]
        public IActionResult Fixture()
        {
            var header = new HeaderViewModel
            {
                Title = WorldCup2022.Fixture,
                Slug = WorldCup2022.FixtureSlug
            };
            return View("~/Views/Event/WorldCup2022/Fixture.cshtml", header);
        }

        [Route("world-cup-2022/bang-xep-hang-world-cup-2022")]
        public async Task<IActionResult> Standing()
        {
            var standingsCriteria = new GetLeagueSeasonStandingCriteria { LeagueId = LeagueIdWC, SeasonId = SeasonIdWC };
            IList<SeasonStandings> standings = await leagueService.GetLeagueSeasonStanding(standingsCriteria);
            var header = new HeaderViewModel
            {
                Title = WorldCup2022.Standing,
                Slug = WorldCup2022.StandingSlug
            };
            var viewModel = new LastestNewsViewModel
            {
                Header = header,
                StandingsViewModel = GroupStandingsByGroupName(standings)
            };
            return View("~/Views/Event/WorldCup2022/DetailStanding.cshtml", viewModel);
        }

        [Route("world-cup-2022/video-world-cup-2022")]
        public async Task<IActionResult> Video()
        {
            var header = new HeaderViewModel
            {
                Title = WorldCup2022.Video,
                Slug = WorldCup2022.VideoSlug
            };
            var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = WorldCup2022.VideoSlug, _Start = 0, _End = Constants.GetTwelveItems + 1 };
            var videos = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
            var viewModel = new VideoViewModel
            {
                Header = header,
                Videos = videos,
                Paging = new PagingModel
                {
                    PageSize = Constants.GetTwelveItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling((cateSlugCriteria.TotalItems - 1) / (double)Constants.GetTwelveItems)
                }
            };
            return View("~/Views/Event/WorldCup2022/Video.cshtml", viewModel);
        }

        [Route("world-cup-2022/story-post")]
        public async Task<IActionResult> StoryPosts()
        {
            var header = new HeaderViewModel
            {
                Title = WorldCup2022.Fixture,
                Slug = WorldCup2022.FixtureSlug
            };
            return View("~/Views/Event/WorldCup2022/StoryPost.cshtml", header);
        }

        public async Task<IActionResult> GetNewsGrid(CategoryPostParams param)
        {
            int pageSize = param.PageSize;
            if (!param.IsVideo)
            {
                var start = (param.Page - 1) * pageSize;
                var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = param.Slugs.Slug1, _Start = start, _End = pageSize };
                IEnumerable<Post> posts = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
                return PartialView("~/Views/Event/WorldCup2022/_NewsGridView.cshtml", posts);
            }
            else
            {
                var outstandingCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = WorldCup2022.VideoSlug, _Start = 0, _End = Constants.GetTwelveItems + 1 };
                var videosOutstanding = await postService.GetPostsByCateSlugAsync(outstandingCriteria);
                var start = param.Page == 1 ? (param.Page - 1) : ((param.Page - 1) * pageSize + 1);
                var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = param.Slugs.Slug1, _Start = start, _End = pageSize };
                IEnumerable<Post> videos = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
                videos = videos.Where(x => x.ID != videosOutstanding.First().ID).ToList();
                return PartialView("~/Views/Event/WorldCup2022/_VideoGridView.cshtml", videos);
            }
        }

        

        public async Task<IActionResult> GetStandings()
        {
            var standingsCriteria = new GetLeagueSeasonStandingCriteria{ LeagueId = LeagueIdWC, SeasonId = SeasonIdWC };
            IList<SeasonStandings> standings = await leagueService.GetLeagueSeasonStanding(standingsCriteria);
            ViewBag.Total = GroupStandingsByGroupName(standings).Count;
            return PartialView("~/Views/Event/WorldCup2022/_Standing.cshtml", GroupStandingsByGroupName(standings));
        }

        public async Task<IActionResult> GetTopScorers()
        {
            var topScorersCriteria = new GetTop10ScorersCriteria{ LeagueId = LeagueIdWC, SeasonId = SeasonIdWC };
            var topScorers = await leagueService.GetTop10ScorersAsync(topScorersCriteria);
            return PartialView("~/Views/Event/WorldCup2022/_TopScorers.cshtml", topScorers);
        }

        [HttpPost]
        public async Task<IActionResult> GetHomepageStandings([FromBody] GetLeagueSeasonStandingCriteria criteria)
        {
            IList<SeasonStandings> standings = await leagueService.GetLeagueSeasonStanding(criteria);
            var groups = GroupStandingsByGroupName(standings);
            return PartialView("~/Views/Event/WorldCup2022/_HomepageStanding.cshtml", groups);
        }

        public List<StandingViewModel> GroupStandingsByGroupName(IList<SeasonStandings> seasonStandings)
        {
            var groupNames = GetGroupNames(seasonStandings);
            var standingsViewModels = new List<StandingViewModel>();

            for (int i = 0; i < groupNames.Count; i++)
            {
                standingsViewModels.Add(new StandingViewModel
                {
                    GroupName = groupNames[i],
                    Standings = seasonStandings.Where(x => x.GroupName == groupNames[i]).ToList()
                });
            }
            return standingsViewModels;
        }

        public List<string> GetGroupNames(IList<SeasonStandings> seasonStandings)
        {
            var groupNames = new List<string>();
            foreach (var item in seasonStandings)
            {
                if (groupNames.Count == 0)
                {
                    groupNames.Add(item.GroupName);
                    continue;
                }

                var check = groupNames.Where(x => x == item.GroupName).ToList();
                if (check.Count == 0)
                {
                    groupNames.Add(item.GroupName);
                }
            }
            return groupNames;
        }
    }
}
