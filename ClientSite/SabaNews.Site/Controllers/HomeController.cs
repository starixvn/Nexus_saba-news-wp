namespace sabanews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Constants;
    using sabanews.Models;
    using sabanews.Services.Interfaces;
    using System;
    using System.Globalization;
    using System.Linq;
    using System.Text;
    using System.Threading.Tasks;

    public class HomeController : Controller
    {
        private readonly IPostService postService;

        public HomeController(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IActionResult> Index()
        {
            var Models = new PostViewModel
            {
                NewPosts = await postService.GetPostsAsync(Constants.NewPostCategory, Constants.FromFirstItem, Constants.GetFourItems),
                FootballPosts = await postService.GetPostsAsync(Constants.FootballCategory, Constants.FromFirstItem, Constants.GetEightItems),
                BasketballPosts = await postService.GetPostsAsync(Constants.BasketballCategory, Constants.FromFirstItem, Constants.GetSixItems),
                TennisPosts = await postService.GetPostsAsync(Constants.TennisCategory, Constants.FromFirstItem, Constants.GetFiveItems),
                VideoPosts = await postService.GetPostsAsync(Constants.VideoCategory, Constants.FromFirstItem, Constants.GetSixItems),
                GuessPosts = await postService.GetPostsAsync(Constants.GuessCategory, Constants.FromFirstItem, Constants.GetThreeItems),
                BackstagePosts = await postService.GetPostsAsync(Constants.BackstageCategory, Constants.FromFirstItem, Constants.GetThreeItems),
                TransferPosts = await postService.GetPostsAsync(Constants.TransferCategory, Constants.FromFirstItem, Constants.GetFiveItems)
            };
            var pageDetail = await postService.GetPageDetailAsync(Constants.Home);
            ViewData[Constants.ViewDataCategoryId] = Constants.Home;
            ViewData[Constants.ViewDataTitle] = pageDetail.Title;
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= pageDetail.Title;
            return View(Models);
        }

        [Route("/tin-tuc/{slug?}/page/{page}")]
        [Route("/tin-tuc/{slug?}")]
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
            var post = await postService.GetPostDetailAsync(Constants.MatchScheduleCategory);
            var pageDetail = await postService.GetPageDetailAsync(Constants.MatchScheduleCategory);
            ViewData[Constants.ViewDataCategoryId] = Constants.MatchScheduleCategory;
            ViewData[Constants.ViewDataTitle] = pageDetail.Title.Replace("%currentdate%", DateTime.Now.ToString(CultureInfo.InvariantCulture));
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= pageDetail.Title;
            return View(post);
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