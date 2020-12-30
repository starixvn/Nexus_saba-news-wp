namespace sabanews.Controllers
{
    using Fanex.Logging;
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Constants;
    using sabanews.Models;
    using sabanews.Services.Interfaces;
    using System;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;

    public class HomeController : Controller
    {
        private readonly IPostService postService;
        private readonly ILogger logger;

        public HomeController(IPostService postService, ILogger logger)
        {
            this.postService = postService;
            this.logger = logger;
        }

        public async Task<IActionResult> Index()
        {
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.HomeSlug);

            var Models = new PostViewModel
            {
                NewPosts = await postService.GetPostsBySlugAsync(Constants.NewPostSlug, Constants.FromFirstItem, Constants.GetFourItems),
                FootballPosts = await postService.GetPostsBySlugAsync(Constants.FootballSlug, Constants.FromFirstItem, Constants.GetEightItems),
                BasketballPosts = await postService.GetPostsBySlugAsync(Constants.BasketballSlug, Constants.FromFirstItem, Constants.GetFourItems),
                TennisPosts = await postService.GetPostsBySlugAsync(Constants.TennisSlug, Constants.FromFirstItem, Constants.GetFourItems),
                VideoPosts = await postService.GetPostsBySlugAsync(Constants.VideoSlug, Constants.FromFirstItem, Constants.GetSixItems),
                GuessPosts = await postService.GetPostsBySlugAsync(Constants.GuessSlug, Constants.FromFirstItem, Constants.GetThreeItems),
                BackstagePosts = await postService.GetPostsBySlugAsync(Constants.BackstageSlug, Constants.FromFirstItem, Constants.GetThreeItems),
                TransferPosts = await postService.GetPostsBySlugAsync(Constants.TransferSlug, Constants.FromFirstItem, Constants.GetFiveItems),
                Category = pageDetail
            };

            ViewData[Constants.ViewDataCategoryId] = Constants.Home;
            ViewData[Constants.ViewDataTitle] = pageDetail.Title;
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= pageDetail.Title;
            return View(Models);
        }

        [Route("/tin-the-thao/{slug?}/page/{page}")]
        [Route("/tin-the-thao/{slug?}")]
        public async Task<IActionResult> Category(string slug = null, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            slug ??= Constants.NewPostSlug;
            var cate = await postService.GetCateDetailAsync(slug);
            var posts = await postService.GetPostsBySlugAsync(slug, start, Constants.Perpage);
            var postsTotal = await postService.GetPostsBySlugAsync(slug, 0, Constants.Total);
            var ViewData = new ViewData
            {
                Title = cate.Title,
                Description = cate.Description ??= cate.Title,
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.Perpage),
                CategoryId = cate.CatId,
                Controller = "category"
            };

            var Model = new CategoryViewModel
            {
                Posts = posts,
                Category = cate,
                ViewData = ViewData
            };
            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Model);
        }

        [Route("/video/{slug?}/page/{page}")]
        [Route("/video/{slug?}")]
        public async Task<IActionResult> Video(string slug = null, int page = 1)
        {
            var start = (page - 1) * Constants.PerpageVideo;
            slug ??= Constants.VideoSlug;
            var cate = await postService.GetCateDetailAsync(slug);
            var posts = await postService.GetPostsBySlugAsync(slug, start, Constants.PerpageVideo);
            var postsTotal = await postService.GetPostsBySlugAsync(slug, 0, Constants.Total);
            var ViewData = new ViewData
            {
                Title = cate.Title,
                Description = cate.Description ??= cate.Title,
                CurrentPage = page,
                PageSize = Constants.PerpageVideo,
                TotalPages = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.PerpageVideo),
                CategoryId = cate.CatId,
                Controller = "video"
            };
            var Model = new CategoryViewModel
            {
                Posts = posts,
                Category = cate,
                ViewData = ViewData
            };
            var view = posts.Any() ? Constants.VideoView : Constants.EmptyBlogView;
            return View(view, Model);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetailBySlugAsync(Constants.MatchScheduleSlug);
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.MatchScheduleSlug);
            var title = pageDetail.Title.Replace("%currentdate%", DateTime.Now.ToString(CultureInfo.InvariantCulture));
            var Model = new PageViewModel
            {
                Post = post,
                Category = pageDetail
            };
            ViewData[Constants.ViewDataCategoryId] = pageDetail.CatId;
            ViewData[Constants.ViewDataTitle] = title;
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= title;
            return View(Model);
        }

        [Route("{slug}")]
        public async Task<IActionResult> Single(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug);
            if (post == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var relatedPosts = await postService.GetRelatedPostAsync(post.ID);
            var randomRelatedPosts = await postService.GetRandomReplatedPostAsync(post.Categories, post.ID);
            var Models = new SingleViewModel
            {
                SinglePost = post,
                RelatedPosts = relatedPosts,
                RandomRelatedPosts = randomRelatedPosts
            };
            ViewData["BaseUrl"] = $"{this.Request.Scheme}://{this.Request.Host}{this.Request.PathBase}";
            ViewData["video"] = post.Type == Constants.VideoPostType;

            return View(Models);
        }

        [Route("Search/page/{page}")]
        [Route("Search")]
        public async Task<IActionResult> Search(string keyword, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var posts = await postService.SearchPostAsync(keyword, start, Constants.Perpage);
            var postsTotal = await postService.SearchPostAsync(keyword, 0, Constants.Total);
            var ViewData = new ViewData
            {
                Title = Constants.SearchTitle + " '" + keyword + "'",
                HeadTitle = Constants.SearchTitle + " '" + keyword + "'",
                Description = Constants.SearchTitle + " '" + keyword + "'",
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.Perpage),
                Keyword = keyword,
                Controller = Constants.Search
            };
            var Model = new CategoryViewModel
            {
                Posts = posts,
                ViewData = ViewData
            };

            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Model);
        }

        [Route("tag/{tagname}/page/{page}")]
        [Route("tag/{tagname}")]
        public async Task<IActionResult> Tag(string tagname, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var posts = await postService.GetPostByTagAsync(tagname, start, Constants.Perpage);
            var postsTotal = await postService.GetPostByTagAsync(tagname, 0, Constants.Total);
            var name = await postService.GetTagNameBySlugAsync(tagname);
            var ViewData = new ViewData
            {
                Title = name,
                HeadTitle = "Tag: '" + tagname + "'",
                Description = name,
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.Perpage),
                Tag = tagname,
                Controller = Constants.Tag
            };
            var Model = new CategoryViewModel
            {
                Posts = posts,
                ViewData = ViewData
            };

            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Model);
        }

        [Route("date/{date}/page/{page}")]
        [Route("date/{date}")]
        public async Task<IActionResult> DateSearch(string date, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var posts = await postService.GetPostByDateAsync(date, start, Constants.Perpage);
            var postsTotal = await postService.GetPostByDateAsync(date, 0, Constants.Total);
            var ViewData = new ViewData
            {
                Title = date + " - Saba Sports",
                HeadTitle = Constants.SearchTitle + " '" + date + "'",
                Description = Constants.SearchTitle + " '" + date + "'",
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.Perpage),
                DateSearch = date,
                Controller = Constants.DateSearch
            };
            var Model = new CategoryViewModel
            {
                Posts = posts,
                ViewData = ViewData
            };
            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Model);
        }
    }
}