namespace SabaNews.Site.Controllers
{
    using System;
    using System.Linq;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Poll;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models.ViewModel.Poll;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;

    public class PostController : Controller
    {
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IPollService pollService;
        private readonly IDataControlService dataControlService;
        private readonly ICacheWrapperService cacheService;

        public PostController(IPostService postService,
            IConfiguration configuration,
            ISessionService sessionService,
            IPollService pollService,
            IDataControlService dataControlService,
            ICacheWrapperService cacheService)
        {
            this.postService = postService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.pollService = pollService;
            this.dataControlService = dataControlService;
            this.cacheService = cacheService;
        }

        [Route("{slug}")]
        public async Task<IActionResult> Single(string slug)
        {
            int postId;
            if(slug.Contains(".html"))
            {
                (slug, postId) = GetPostInfoFromSlug(slug);
                if(postId == 0)
                {
                    return View(Constants.EmptyBlogView);
                }

            }
            else
            {
                var summary = await postService.GetPostDetailSummaryAsync(slug);
                if(summary == null)
                {
                    return View(Constants.EmptyBlogView);
                }
                else
                {
                    return RedirectToActionPermanent("Single", "Post", new { slug = summary.Uri });
                }

            }
            var post = await postService.GetPostDetailAsync(postId);
            if (post == null)
            {
                return View(Constants.EmptyBlogView);
            }
            if(post.Slug != slug)
            {
                return RedirectToActionPermanent("Single", "Post", new { slug = post.Uri });
            }
            SavePost savePost = null;
            var isLogin = sessionService.User != null;
            if (isLogin)
            {
                savePost = sessionService.User.HandleSavePosts.Find(val => val.PostID == post.ID);
            }
            var matchId = PostHelper.GetMatchInfoFromContent(post.PostContent);
            var cateInfo = GetCateInfo(post.CatesName, post.CategoriesSlug);
            var cateParents = await postService.GetCateParentsAsync(cateInfo.Slug);
            cateInfo.Parent = PostHelper.GetTopCateParent(cateParents);
            cateInfo.Url = Url.Action("Category", "Home", new { slug = cateInfo.Slug + CategoryKey.Suffix});
            var domain = configuration.GetValue<string>("BaseUrl");
            post.SeoTitle = string.IsNullOrEmpty(post.SeoTitle) ? post.PostTitle : Helper.ReplaceStringFromVarSeo(post.SeoTitle, post.PostTitle);
            post.SeoDescription = string.IsNullOrEmpty(post.SeoDescription) ? post.PostExcerpt : Helper.ReplaceStringFromVarSeo(post.SeoDescription, post.PostTitle);
            var model = new SingleViewModel
            {
                SinglePost = post,
                IsLogin = isLogin,
                SavePost = savePost,
                AbsoluteUrl = $"{domain}{slug}-{post.ID}.html",
                IsVideo = PostHelper.IsVideo(cateParents),
                CateInfo = cateInfo,
                MatchId = matchId
            };

            return View(model);
        }

        private static (string, int) GetPostInfoFromSlug(string slug)
        {
            var postId = 0;
            slug = slug.Replace(".html","");
            var arrSlug = slug.Split("-");
            if(!int.TryParse(arrSlug[arrSlug.Count() - 1], out postId))
            {
                return (slug, postId);
            }
            slug = slug.Replace("-" + postId, "");
            return (slug, postId);
        }


        [Route("kich-hoat-tai-khoan-saba-sports")]
        public IActionResult ActiveAccount()
        {
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> GetPollData(int questId, int themeId)
        {
            var IsUsingCache = configuration.GetValue<bool>("IsEnabledUseFanexCaching");
            PollViewModel result;
            var clientIP = GetClientIP();
            var criteria = new GetPollDetailCriteria
            {
                QuestId = questId,
                ThemeId = themeId,
                UserIp = clientIP
            };
            if(IsUsingCache)
            {
                var cacheKey = $"{CacheKey.PollData}-{questId}";
                result = await cacheService.GetAsync<PollViewModel>(cacheKey);
                if(result == null)
                {
                    result = await pollService.GetPollDetailAsync(criteria);
                    await cacheService.SetAsync(cacheKey, result,
                        new CacheItemOptions
                        {
                            RelativeExpiration = TimeSpan.FromMinutes(Constants.CachePollDataTimeoutMinutes)
                        });
                }
            }
            else
            {
                result = await pollService.GetPollDetailAsync(criteria);
            }
            return PartialView("_Poll", result);
        }

        [HttpPost]
        public async Task<IActionResult> VotePoll(int questId, int answerId, int themeId)
        {
            var clientIP = GetClientIP();
            var result = await pollService.VotePollAsync(questId, answerId, clientIP);
            if (result.IsSuccessful)
            {
                await cacheService.Remove($"{CacheKey.PollData}-{questId}");
                return await GetPollData(questId, themeId);
            }
            return Json(result);
        }


        [HttpPost]
        public async Task<IActionResult> GetPostsSameCategory(string slug, long postId)
        {
            var criteria =  new GetPostsByCateSlugPagingCriteria
            {
                _Start = Constants.FromFirstItem,
                _End = Constants.GetElevenItems,
                _CategorySlug = slug
            };
            var posts = await postService.GetPostsByCateSlugPagingAsync(criteria);
            posts = posts.Where(item => item.ID != postId).Take(Constants.GetTenItems).ToList();
            return PartialView("_SameCategory", posts);
        }

        private static CateInfo GetCateInfo(string cateName, string cateSlug)
        {
            var slug = cateSlug;
            var name = cateName;
            var listCateSlug = cateSlug.Split(',');
            var listCateName = cateName.Split(',');
            var subCateSlug = listCateSlug.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
            var subCateName = listCateName.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
            slug = subCateSlug.Count > 0 ? subCateSlug[0] : listCateSlug[0];
            name = subCateName.Count > 0 ? subCateName[0] : listCateName[0];
            return new CateInfo { Slug = slug.Trim(), Name = name.Trim() };
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


        [HttpPost]
        [Route("api/post/related-posts")]
        public IActionResult GetRelatedPosts(string cateSlug, int postID, string title)
        {
            return ViewComponent("RelatedPosts", new { cateSlug, postID, title });
        }

        [HttpPost]
        [Route("api/post/sidebar-posts")]
        public IActionResult GetSidebarPosts(string cateSlug, int postID, int limitItem)
        {
            return ViewComponent("Sidebar", new { cateSlug, postID, limitItem });
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
            var posts = await dataControlService.GetRecentPostsAsync();
            posts = posts.Take(Constants.GetFiveItems);
            return Ok(posts);
        }

        [HttpPost]
        [Route("api/post/today-fixture")]
        public async Task<IActionResult> GetTodayFixturePost()
        {
            var criteria = new GetPostsByTagCriteria
            {
                _Tag = Constants.TodayLiveFixtureTag,
                _Start = Constants.FromFirstItem,
                _End = Constants.GetFiveItems
            };
            var posts = await postService.GetPostByTagAsync(criteria);
            return Ok(posts);
        }

         [HttpPost]
        [Route("api/GetGuessSidebar")]
        public async Task<IActionResult> GetGuessSidebar(string slug, int limitItem)
        {

            var posts = await dataControlService.GetCategoryPostsAsync(slug);
            posts = posts.Take(limitItem);
            ViewBag.Slug = slug;
            return PartialView("_GuessSidebar", posts);
        }

        [HttpPost]
        [Route("api/GetTodayMatches")]
        public async Task<IActionResult> GetTodayMatches()
        {
            var matches = await dataControlService.GetTodayMatches();
            return Json(matches);
        }

        public string GetClientIP()
        {
            var clientIP = HttpContext.Request.Headers["x-forwarded-for"].ToString();
            clientIP = !string.IsNullOrWhiteSpace(clientIP) ? clientIP.Split(',')[0] : HttpContext.Connection.RemoteIpAddress.ToString();
            return clientIP;
        }
    }
}