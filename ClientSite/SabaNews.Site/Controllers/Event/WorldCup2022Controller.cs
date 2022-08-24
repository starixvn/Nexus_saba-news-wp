namespace SabaNews.Site.Controllers.Event
{
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Posts;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;
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
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IUserService userService;
        private const long LeagueIdWC = WorldCup2022.LeagueIdWorldCup2022;
        private const long SeasonIdWC = WorldCup2022.SeasonIdWorldCup2022;
        private const string CategorySlugWC = WorldCup2022.HomepageSlug;

        public WorldCup2022Controller(
            IPostService postService,
            IDataControlService dataControlService,
            IMatchService matchService,
            ILeagueService leagueService,
            IConfiguration configuration,
            ISessionService sessionService,
            IUserService userService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
            this.matchService = matchService;
            this.leagueService = leagueService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.userService = userService;
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

        [Route("world-cup-2022/{slug}")]
        public async Task<IActionResult> PostDetailWC(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            if (post == null)
            {
                return View(Constants.EmptyBlogView);
            }
            SavePost savePost = null;
            var isLogin = sessionService.User != null;
            if (isLogin)
            {
                var user = await userService.GetUserByIdAsync(sessionService.User.Id);
                savePost = user.HandleSavePosts.Find(val => val.PostID == post.ID);
            }
            var cateInfo = GetCateInfo(post.CatesName, post.CategoriesSlug);
            var cateParents = await postService.GetCateParentsAsync(cateInfo.Slug);
            cateInfo.Url ??= PostHelper.GetCategoryLink(cateParents);
            var domain = configuration.GetValue<string>("BaseUrl");
            var model = new SingleViewModel
            {
                SinglePost = post,
                IsLogin = isLogin,
                SavePost = savePost,
                AbsoluteUrl = $"{domain}{slug}",
                IsVideo = PostHelper.CheckIsVideo(cateParents),
                CateInfo = cateInfo
            };

            return View("~/Views/Event/WorldCup2022/PostDetail.cshtml", model);
        }

        [HttpPost]
        [Route("world-cup-2022/related-posts")]
        public IActionResult GetRelatedPosts(string cateSlug, int postID)
        {
            return ViewComponent("RelatedPostsWC", new { cateSlug, postID });
        }

        [HttpPost]
        [Route("world-cup-2022/sidebar-posts")]
        public IActionResult GetSidebarPosts(string cateSlug, int postID)
        {
            return ViewComponent("SidebarWC", new { cateSlug, postID });
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

        [Route("lich-thi-dau/world-cup/{match}/{matchId:long}")]
        public IActionResult MatchDetail(long matchId)
        {
            var model = new MatchDetailModel
            {
                Header = new HeaderViewModel
                {
                    Title = WorldCup2022.Fixture,
                    Slug = WorldCup2022.FixtureSlug
                },
                MatchId = matchId
            };
            return View("~/Views/Event/WorldCup2022/MatchDetail.cshtml", model);
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
            standings[0].TeamFullNameEN += "testtooltipdemo";
            return PartialView("~/Views/Event/WorldCup2022/_Standing.cshtml", GroupStandingsByGroupName(standings));
        }

        public async Task<IActionResult> GetTopScorers()
        {
            var topScorersCriteria = new GetTop10ScorersCriteria{ LeagueId = LeagueIdWC, SeasonId = SeasonIdWC };
            var topScorers = await leagueService.GetTop10ScorersAsync(topScorersCriteria);
            topScorers[0].PlayerFullNameEN += "testtooltipdemo";
            return PartialView("~/Views/Event/WorldCup2022/_TopScorers.cshtml", topScorers);
        }

        [HttpPost]
        public async Task<IActionResult> GetHomepageStandings([FromBody] GetLeagueSeasonStandingCriteria criteria)
        {
            IList<SeasonStandings> standings = await leagueService.GetLeagueSeasonStanding(criteria);
            standings[0].TeamFullNameEN += "testtooltipdemo";

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

        private static CateInfo GetCateInfo(string cateName, string cateSlug)
        {
            var slug = cateSlug;
            var name = cateName;
            var cateEvent = GetEventInfo(cateSlug);
            if (cateEvent != null)
            {
                return cateEvent;
            }
            if (cateSlug.Contains(Constants.CollectionSlug) || (cateSlug.Contains(Constants.BasketballSlug)
                && !cateSlug.Contains(Constants.BasketballVideoSlug)) || cateSlug.Contains(Constants.TennisSlug))
            {
                return new CateInfo { Slug = Constants.OtherPostSlug, Name = Constants.OtherPosts };
            }

            var listCateSlug = cateSlug.Split(',');
            var listCateName = cateName.Split(',');
            var subCateSlug = listCateSlug.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
            var subCateName = listCateName.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
            slug = subCateSlug.Count > 0 ? subCateSlug[0] : listCateSlug[0];
            name = subCateName.Count > 0 ? subCateName[0] : listCateName[0];
            return new CateInfo { Slug = slug.Trim(), Name = name.Trim() };
        }

        private static CateInfo GetEventInfo(string cateSlug)
        {
            var cateEvents = PostHelper.GetEventCates();
            var listCateSlug = cateSlug.Split(',');
            foreach (var slug in listCateSlug)
            {
                var cate = cateEvents.Find(item => item.Slug == slug.Trim());
                if (cate != null)
                {
                    return cate;
                }
            }
            return null;

        }
    }
}
