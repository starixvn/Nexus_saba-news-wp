namespace SabaNews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
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
        private readonly IBannerService bannerService;
        private readonly ILogServiceProvider logService;

        public HomeController(IPostService postService,
            IDataControlService dataControlService,
            IConfiguration configuration,
            IBannerService bannerService,
            ILogServiceProvider logService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
            this.configuration = configuration;
            this.bannerService = bannerService;
            this.logService = logService;
        }

        public async Task<IActionResult> Index()
        {
            logService.WriteLogFileInfo("Home");
            var viewModel = await dataControlService.GetHomePageViewModel();
            var banner = await bannerService.GetBannerInfoAsync();
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
            viewModel.Banner = banner;
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

            viewModel.Header.ActiveCategory = slug;
            if (slug == Constants.FootballInside || slug == Constants.FootballOutside)
            {
                viewModel.Header.HeadTitle = viewModel.Posts.First().CategoryParent;
                viewModel.Header.IsChildren = true;
                viewModel.Header.ActiveCategory = Constants.FootballSlug;
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
            var model = new PageViewModel
            {
                Post = post,
                Category = pageDetail,
                Header = header
            };
           
            return View(model);
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
            var model = new PageViewModel
            {
                Category = pageDetail,
                Header = header
            };
            
            return View(model);
        }


        [Route("Search/page/{page}")]
        [Route("Search")]
        public async Task<IActionResult> Search(string keyword, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var criteria = new SearchPostsCriteria { _Keyword = keyword ??= "", _Start = start, _End = Constants.Perpage };
            var posts = await postService.SearchPostAsync(criteria);
            var totalItems = criteria.TotalItems;
            var header = new HeaderViewModel
            {
                Title = Constants.SearchTitle + " '" + keyword + "'",
                HeadTitle = Constants.SearchTitle + " '" + keyword + "'",
                Description = Constants.SearchTitle + " '" + keyword + "'",
                Keyword = keyword,
                Controller = Constants.Search
            };
            var paging = new PagingModel
            {
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage)
            };
            var model = new CategoryViewModel
            {
                Posts = posts,
                Header = header,
                Paging = paging
            };

            var view = (posts != null && posts.Any()) ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, model);
        }

        [Route("tag/{tagname}/page/{page}")]
        [Route("tag/{tagname}")]
        public async Task<IActionResult> Tag(string tagname, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var criteria = new GetPostsByTagCriteria { _Tag = tagname.Trim(), _Start = start, _End = Constants.Perpage };
            var posts = await postService.GetPostByTagAsync(criteria);
            var totalItems = criteria.TotalItems;
            var tag = posts.Any() ? posts.First().Tags : tagname;
           
            var header = new HeaderViewModel
            {
                Title = string.Concat(tag, " | ", Constants.SiteName),
                HeadTitle = "Tag: '" + tag + "'",
                Keyword = tagname,
                Controller = Constants.Tag
            };
            var paging = new PagingModel
            {
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage)
            };
            var category = new Category
            {
                OgTitle = string.Concat(tag, " | ", Constants.SiteName),
                OgImage = string.Concat(configuration.GetValue<string>("BaseUrl"), "images/sabasports.svg"),
                Url = Url.Action("Tag", "Home", new { tagname }, Request.Scheme)
            };
            var model = new CategoryViewModel
            {
                Posts = posts,
                Header = header,
                Category = category,
                Paging = paging
            };

            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, model);
        }
    }
}