namespace SabaNews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;

    public class HomeController : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;

        public HomeController(IPostService postService, IDataControlService dataControlService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
        }

        public async Task<IActionResult> Index()
        {
            var viewModel = await dataControlService.GetHomePageViewModel();

            ViewData[Constants.ViewDataCategoryId] = Constants.Home;
            ViewData[Constants.ViewDataTitle] = viewModel.Category.SEOTitle ??= viewModel.Category.Title;
            ViewData[Constants.ViewDataDescription] = viewModel.Category.Description;

            return View(viewModel);
        }

        [Route("/tin-the-thao/{slug?}/page/{page}")]
        [Route("/tin-the-thao/{slug?}")]
        public async Task<IActionResult> Category(string slug = null, int page = 1)
        {
            slug ??= Constants.NewPostSlug;
            var viewModel = await dataControlService.GetCategoryViewModel(slug, page);
            var view = (viewModel != null && viewModel.Posts.Any()) ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, viewModel);
        }

        [Route("/video/{slug?}/page/{page}")]
        [Route("/video/{slug?}")]
        public async Task<IActionResult> Video(string slug = null, int page = 1)
        {
            slug ??= Constants.VideoSlug;
            var viewModel = await dataControlService.GetCategoryViewModel(slug, page);
            var view = (viewModel != null && viewModel.Posts.Any()) ? Constants.VideoView : Constants.EmptyBlogView;
            return View(view, viewModel);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetailBySlugAsync(Constants.MatchScheduleSlug, Constants.pageType);
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.MatchScheduleSlug);
            var title = pageDetail.SEOTitle.Replace("%current_date_custom%", DateTime.UtcNow.ToString("MM/dd/yyyy", CultureInfo.InvariantCulture));
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
            var post = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
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
            var postTemplate = await postService.SearchPostAsync(keyword, start, Constants.Perpage);
            var totalItems = postTemplate.TotalItems;
            var ViewData = new ViewData
            {
                Title = Constants.SearchTitle + " '" + keyword + "'",
                HeadTitle = Constants.SearchTitle + " '" + keyword + "'",
                Description = Constants.SearchTitle + " '" + keyword + "'",
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage),
                Keyword = keyword,
                Controller = Constants.Search
            };
            var Model = new CategoryViewModel
            {
                Posts = postTemplate.Posts,
                ViewData = ViewData
            };

            var view = postTemplate.Posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Model);
        }

        [Route("tag/{tagname}/page/{page}")]
        [Route("tag/{tagname}")]
        public async Task<IActionResult> Tag(string tagname, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var postTemplate = await postService.GetPostByTagAsync(tagname, start, Constants.Perpage);
            var posts = postTemplate.Posts;
            var totalItems = postTemplate.TotalItems;
            var tag = posts.Any() ? posts.First().Tags : tagname;
            var ViewData = new ViewData
            {
                Title = tag,
                HeadTitle = "Tag: '" + tag + "'",
                Description = tag,
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage),
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
            var postTemplate = await postService.GetPostByDateAsync(date, start, Constants.Perpage);
            var posts = postTemplate.Posts;
            var totalItems = postTemplate.TotalItems;
            var ViewData = new ViewData
            {
                Title = date + " - Saba Sports",
                HeadTitle = Constants.SearchTitle + " '" + date + "'",
                Description = Constants.SearchTitle + " '" + date + "'",
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage),
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