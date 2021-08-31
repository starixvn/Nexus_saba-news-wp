namespace SabaNews.Site.Controllers
{
    using System;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;

    public class PostController : Controller
    {
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IProfileService profileService;
        private readonly IStatisticService statisticService;

        public PostController(IPostService postService,
            IConfiguration configuration,
            ISessionService sessionService,
            IProfileService profileService,
            IStatisticService statisticService)
        {
            this.postService = postService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.profileService = profileService;
            this.statisticService = statisticService;
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
                Slug = Constants.VideoSlug,
                Start = Constants.FromFirstItem,
                End = Constants.GetElevenItems,
                PostID = post.ID
            };
            var recentPosts = await postService.GetRecentPostsAsync(postParam);
            SavePost savePost = null;
            var isLogin = sessionService.User != null;
            if (isLogin)
            {
                var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
                savePost = user.HandleSavePosts.Find(val => val.PostID == post.ID);
            }
            (string slug, string name) cateInfo = GetCateInfo(post.CatesName, post.CategoriesSlug);
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
                CateName = cateInfo.name
            };
            await postService.UpdateViewAsync(post.ID, post.Type == Constants.VideoPostType);
            return View(model);
        }

        private static (string, string) GetCateInfo(string cateName, string cateSlug)
        {
            var slug = cateSlug;
            var name = cateName;
            if (cateSlug.Contains(","))
            {
                var listCateSlug = cateSlug.Split(',');
                var listCateName = cateName.Split(',');
                var exceptCateSlug = listCateSlug.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
                var exceptCateName = listCateName.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
                slug = exceptCateSlug.Count > 0 ? exceptCateSlug[0] : listCateSlug[0];
                name = exceptCateName.Count > 0 ? exceptCateName[0] : listCateName[0];
            }

            return (slug.Trim(), name.Trim());
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
    }
}