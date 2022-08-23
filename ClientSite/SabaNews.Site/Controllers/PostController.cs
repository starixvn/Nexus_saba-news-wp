namespace SabaNews.Site.Controllers
{
    using System;
    using System.Linq;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;

    public class PostController : Controller
    {
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IUserService userService;
        private readonly IStatisticService statisticService;
        private readonly IPollService pollService;
        private readonly IDataControlService dataControlService;

        public PostController(IPostService postService,
            IConfiguration configuration,
            ISessionService sessionService,
            IUserService userService,
            IStatisticService statisticService,
            IPollService pollService,
            IDataControlService dataControlService)
        {
            this.postService = postService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.userService = userService;
            this.statisticService = statisticService;
            this.pollService = pollService;
            this.dataControlService = dataControlService;
        }

        [Route("{slug}")]
        public async Task<IActionResult> Single(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            if (post == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var postParam = new GetRecentPostsParam
            {
                Start = Constants.FromFirstItem,
                End = Constants.GetElevenItems,
                PostID = post.ID
            };
            var recentPosts = await postService.GetRecentPostsAsync(postParam);
            SavePost savePost = null;
            var isLogin = sessionService.User != null;
            if (isLogin)
            {
                var user = await userService.GetUserByIdAsync(sessionService.User.Id);
                savePost = user.HandleSavePosts.Find(val => val.PostID == post.ID);
            }
            (string slug, string name) cateInfo = GetCateInfo(post.CatesName, post.CategoriesSlug);
            var cateParents = await postService.GetCateParentsAsync(cateInfo.slug);
            var cateUrl = PostHelper.GetCategoryLink(cateParents);
            var topParentCate = PostHelper.GetTopCateParent(cateParents);
            var domain = configuration.GetValue<string>("BaseUrl");
            var model = new SingleViewModel
            {
                SinglePost = post,
                RecentPosts = recentPosts,
                IsLogin = isLogin,
                SavePost = savePost,
                AbsoluteUrl = $"{domain}{slug}",
                IsVideo = post.Type == Constants.VideoPostType,
                CateSlug = cateInfo.slug,
                CateName = cateInfo.name,
                CateUrl = cateUrl,
                CateParent = topParentCate
            };
            
            return View(model);
        }

        

        [HttpPost]
        public async Task<IActionResult> GetPollData(int questId, int themeId)
        {
            var clientIP = GetClientIP();
            var criteria = new GetPollCriteria
            {
                QuestId = questId,
                ThemeId = themeId,
                UserIp = clientIP
            };
            var model = await pollService.GetPollAsync(criteria);
            return PartialView("_Poll", model);
        }

        [HttpPost]
        public async Task<IActionResult> VotePoll(int questId, int answerId, int themeId)
        {
            var clientIP = GetClientIP();
            var result = await pollService.VotePollAsync(questId, answerId, clientIP);
            if (result.IsSuccessful)
            {
                return await GetPollData(questId, themeId);
            }
            return Json(result);
        }


        private static (string, string) GetCateInfo(string cateName, string cateSlug)
        {
            var slug = cateSlug;
            var name = cateName;
            if (cateSlug.Contains(Constants.CollectionSlug) || (cateSlug.Contains(Constants.BasketballSlug) 
                && !cateSlug.Contains(Constants.BasketballVideoSlug)) || cateSlug.Contains(Constants.TennisSlug))
            {
                return (Constants.OtherPostSlug, Constants.OtherPosts);
            }
            if (cateSlug.Contains(","))
            {
                var listCateSlug = cateSlug.Split(',');
                var listCateName = cateName.Split(',');
                var subCateSlug = listCateSlug.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
                var subCateName = listCateName.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
                slug = subCateSlug.Count > 0 ? subCateSlug[0] : listCateSlug[0];
                name = subCateName.Count > 0 ? subCateName[0] : listCateName[0];
                
            }
            return (slug.Trim(), name.Trim());
        }

        [HttpPost]
        [Route("api/post/update-post-view")]
        public async Task<IActionResult> UpdatePostView(int postId, bool isVideo)
        {
            var result = await postService.UpdateViewAsync(postId, isVideo);
            return Ok(result);
        }

        [HttpPost]
        [Route("api/post/view-video")]
        public async Task<IActionResult> ViewVideo(int postId)
        {
            var viewVideoResult = await postService.UpdateVideoView(postId);
            return Ok(viewVideoResult);
        }

        [HttpGet]
        [Route("api/post-view")]
        public async Task<IActionResult> GetPostViewStatistic(DateTime fromDate, DateTime toDate)
        {
            var viewPostStatistic = await statisticService.GetPostViewStatistic(fromDate, toDate);
            return Ok(viewPostStatistic);
        }

        [HttpPost]
        [Route("api/post/related-posts")]
        public IActionResult GetRelatedPosts(string cateSlug, int postID)
        {
            return ViewComponent("RelatedPosts", new { cateSlug, postID });
        }

        [HttpPost]
        [Route("api/post/sidebar-posts")]
        public IActionResult GetSidebarPosts(string cateSlug, int postID)
        {
            return ViewComponent("Sidebar", new { cateSlug, postID });
        }

        [HttpPost]
        [Route("api/banner")]
        public async Task<IActionResult> GetBanner()
        {
            var banner = await dataControlService.GetBannerData();
            return Ok(banner);
        }

        [HttpPost]
        [Route("api/post/lastestNews")]
        public async Task<IActionResult> GetLastestNews()
        {
            var posts = await postService.GetRecentPostsAsync(new GetRecentPostsParam { Start = Constants.FromFirstItem, End = Constants.GetFiveItems});
            return Ok(posts);
        }


        public string GetClientIP()
        {
            var clientIP = HttpContext.Request.Headers["x-forwarded-for"].ToString();
            clientIP = !string.IsNullOrWhiteSpace(clientIP) ? clientIP.Split(',')[0] : HttpContext.Connection.RemoteIpAddress.ToString();
            return clientIP;
        }
    }
}