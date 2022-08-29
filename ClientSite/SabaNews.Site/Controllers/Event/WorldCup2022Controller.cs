namespace SabaNews.Site.Controllers.Event
{
    using HtmlAgilityPack;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Posts;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.StoryPost;
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
    using System.Web;

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
            model.OverviewPageUrl = $"/{WorldCup2022.HomepageSlug}/{WorldCup2022.OverviewPageSlug}";
            return View("~/Views/Event/WorldCup2022/Index.cshtml", model);
        }

        [Route("world-cup-2022/{slug}")]
        public async Task<IActionResult> PostDetailWC(string slug)
        {
            var postWC = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            if (postWC == null)
            {
                return View(Constants.EmptyBlogView);
            }
            SavePost savePostWC = null;
            var isUserLogin = sessionService.User != null;
            if (isUserLogin)
            {
                var user = await userService.GetUserByIdAsync(sessionService.User.Id);
                savePostWC = user.HandleSavePosts.Find(val => val.PostID == postWC.ID);
            }
            var cateInfoWC = GetCateInfoWC(postWC.CatesName, postWC.CategoriesSlug);
            var cateParentsWC = await postService.GetCateParentsAsync(cateInfoWC.Slug);
            cateInfoWC.Url ??= PostHelper.GetCategoryLink(cateParentsWC);
            var domain = configuration.GetValue<string>("BaseUrl");
            var viewModel = new SingleViewModel
            {
                SinglePost = postWC,
                IsLogin = isUserLogin,
                SavePost = savePostWC,
                AbsoluteUrl = $"{domain}{slug}",
                IsVideo = PostHelper.CheckIsVideo(cateParentsWC),
                CateInfo = cateInfoWC
            };

            return View("~/Views/Event/WorldCup2022/PostDetail.cshtml", viewModel);
        }

        [HttpPost]
        [Route("world-cup-2022/related-posts")]
        public IActionResult GetRelatedPostsWC(string cateSlug, int postID)
        {
            return ViewComponent("RelatedPostsWC", new { cateSlug, postID });
        }

        [HttpPost]
        [Route("world-cup-2022/sidebar-posts")]
        public IActionResult GetSidebarPostsWC(string cateSlug, int postID)
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
                CategorySlug = CategorySlugWC,
                Paging = new PagingModel{
                    PageSize = Constants.GetTenItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)Constants.GetTenItems)
                }
            };
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
                CategorySlug = WorldCup2022.CommentarySlug,
                Paging = new PagingModel
                {
                    PageSize = Constants.GetTenItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)Constants.GetTenItems)
                }
            };
            return View("~/Views/Event/WorldCup2022/LastestNewsWC.cshtml", viewModel);
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
            var model = new MatchDetailViewModel
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

        [Route("world-cup-2022/story-post-world-cup-2022")]
        public async Task<IActionResult> StoryPost()
        {
            const int pageSize = Constants.GetEightItems;
            var header = new HeaderViewModel
            {
                Title = WorldCup2022.StoryPost,
                Slug = WorldCup2022.StoryPostSlug
            };
            var cateSlugCriteria = new GetPostsByCateSlugCriteria 
            { 
                _CategorySlug = WorldCup2022.StoryPostSlug,
                _Start = 0, 
                _End = pageSize,
                PostType = Constants.storyPostType
            };
            var stories = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
            var model = new LastestNewsViewModel
            {
                Header = header,
                Posts = stories,
                Paging = new PagingModel
                {
                    PageSize = pageSize,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)pageSize)
                }
            };
            
            return View("~/Views/Event/WorldCup2022/StoryPost.cshtml", model);
        }

        [Route("world-cup-2022/story-post-world-cup-2022/{slug}")]
        public async Task<IActionResult> StoryPostDetail(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug, Constants.storyPostType);
            if (post == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var stories = PostHelper.GetListImageFromPostContent(post.PostContent);
            var model = new StoryPostDetailViewModel
            {
                Post = post,
                Stories = stories,
                BaseUrl = configuration.GetValue<string>("BaseUrl"),
                BackUrl = $"/{WorldCup2022.HomepageSlug}/{WorldCup2022.StoryPostSlug}"
            };
            return PartialView("~/Views/Event/WorldCup2022/StoryPostDetail.cshtml", model);
        }

        [HttpPost]
        public async Task<IActionResult> GetStoryPostGrid(CategoryPostParams param)
        {
            var start = (param.Page - 1) * param.PageSize;
            var cateSlugCriteria = new GetPostsByCateSlugCriteria
            {
                _CategorySlug = param.Slug,
                _Start = start,
                _End = param.PageSize,
                PostType = Constants.storyPostType
            };
            var posts = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
            if (!posts.Any())
            {
                return Ok(false);
            }
            return PartialView("~/Views/Event/WorldCup2022/_StoryPost.cshtml", posts);
        }

        public async Task<IActionResult> GetNewsGrid(CategoryPostParams param)
        {
            int pageSize = param.PageSize;
            if (!param.IsVideo)
            {
                var start = (param.Page - 1) * pageSize;
                var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = param.Slug, _Start = start, _End = pageSize };
                IEnumerable<Post> posts = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
                return PartialView("~/Views/Event/WorldCup2022/_NewsGridView.cshtml", posts);
            }
            else
            {
                var outstandingCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = WorldCup2022.VideoSlug, _Start = (param.Page - 1) * pageSize, _End = Constants.GetTwelveItems + 1 };
                var videosOutstanding = await postService.GetPostsByCateSlugAsync(outstandingCriteria);
                var start = (param.Page - 1) * pageSize;
                var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = param.Slug, _Start = start, _End = pageSize + 1 };
                IEnumerable<Post> videos = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
                videos = videos.Where(x => x.ID != videosOutstanding.First().ID).ToList();
                if(videos.Count() > 12) {
                    var idlast = videos.Last().ID;
                    videos = videos.Where(x => x.ID != idlast).ToList();
                }
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

        private static CateInfo GetCateInfoWC(string cateName, string cateSlugWC)
        {
            var slugWC = cateSlugWC;
            var cateNameWC = cateName;
            var cateEventWC = GetEventWCInfo(cateSlugWC);
            if (cateEventWC != null)
            {
                return cateEventWC;
            }
            if (cateSlugWC.Contains(Constants.CollectionSlug) || (cateSlugWC.Contains(Constants.BasketballSlug)
                && !cateSlugWC.Contains(Constants.BasketballVideoSlug)) || cateSlugWC.Contains(Constants.TennisSlug))
            {
                return new CateInfo { Slug = Constants.OtherPostSlug, Name = Constants.OtherPosts };
            }

            var listCateSlugWC = cateSlugWC.Split(',');
            var listCateNameWC = cateName.Split(',');
            var subCateSlugWC = listCateSlugWC.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
            var subCateNameWC = listCateNameWC.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
            slugWC = subCateSlugWC.Count > 0 ? subCateSlugWC[0] : listCateSlugWC[0];
            cateNameWC = subCateNameWC.Count > 0 ? subCateNameWC[0] : listCateNameWC[0];
            return new CateInfo { Slug = slugWC.Trim(), Name = cateNameWC.Trim() };
        }

        private static CateInfo GetEventWCInfo(string cateSlugWC)
        {
            var cateEventWC = PostHelper.GetEventCates();
            var listCateSlugWC = cateSlugWC.Split(',');
            foreach (var slug in listCateSlugWC)
            {
                var cate = cateEventWC.Find(item => item.Slug == slug.Trim());
                if (cate != null)
                {
                    return cate;
                }
            }
            return null;

        }
    }
}
