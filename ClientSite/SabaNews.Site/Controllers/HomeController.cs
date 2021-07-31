namespace SabaNews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
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
        private readonly IConfiguration configuration;

        public HomeController(IPostService postService,
            IDataControlService dataControlService,
            IConfiguration configuration)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
            this.configuration = configuration;
        }

        public async Task<IActionResult> Index()
        {
            var viewModel = await dataControlService.GetHomePageViewModel();
            var domain = configuration.GetValue<string>("BaseUrl");
            var header = new HeaderViewModel
            {
                CategoryId = Constants.Home,
                CategorySlug = Constants.Empty,
                Title = viewModel.Category.SEOTitle ??= viewModel.Category.Title,
                Description = viewModel.Category.Description
            };
            viewModel.Header = header;
            viewModel.Url = domain;
            viewModel.RSSUrl = $"{domain}tin-moi-nhat.rss";
            return View(viewModel);
        }

        [Route("/tin-the-thao/{slug?}/page/{page}")]
        [Route("/tin-the-thao/{slug?}")]
        public async Task<IActionResult> Category(string slug = null, int page = 1)
        {
            slug ??= Constants.NewPostSlug;
            var viewModel = await dataControlService.GetCategoryViewModel(slug, page);
            if (viewModel == null)
            {
                return View(Constants.EmptyBlogView);
            }

            viewModel.ViewData.ActiveCategory = slug;
            if (slug == Constants.FootballInside || slug == Constants.FootballOutside)
            {
                viewModel.ViewData.HeadTitle = viewModel.Posts.First().CategoryParent;
                viewModel.ViewData.IsChildren = true;
                viewModel.ViewData.ActiveCategory = Constants.FootballSlug;
            }
            return View(Constants.BlogView, viewModel);
        }

        [Route("/video/page/{page}")]
        [Route("/video/")]
        public async Task<IActionResult> Video(int page = 1)
        {
            var viewModel = await dataControlService.GetCategoryViewModel(Constants.VideoSlug, page);
            var view = (viewModel != null && viewModel.Posts.Any()) ? Constants.VideoView : Constants.EmptyBlogView;
            return View(view, viewModel);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetailBySlugAsync(Constants.MatchScheduleSlug, Constants.pageType);
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.MatchScheduleSlug);
            var title = pageDetail.SEOTitle != null ? pageDetail.SEOTitle.Replace(Constants.CurrentDateVar, DateTime.UtcNow.ToString("dd/MM/yyyy", CultureInfo.InvariantCulture)) : pageDetail.Title;
            var header = new HeaderViewModel
            {
                CategorySlug = Constants.MatchScheduleSlug,
                CategoryId = pageDetail.CatId,
                Title = title,
                Description = pageDetail.Description ??= title

            };
            var Model = new PageViewModel
            {
                Post = post,
                Category = pageDetail,
                Header = header
            };
           
            return View(Model);
        }

        [Route("warning")]
        public async Task<IActionResult> Warning()
        {
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.WarningSlug);
            var title = pageDetail.SEOTitle ?? pageDetail.Title;
            var header = new HeaderViewModel
            {
                CategorySlug = Constants.WarningSlug,
                CategoryId = pageDetail.CatId,
                Title = title,
                Description = pageDetail.Description ??= title

            };
            var Model = new PageViewModel
            {
                Category = pageDetail,
                Header = header
            };
            
            return View(Model);
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
    }
}