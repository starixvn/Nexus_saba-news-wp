namespace SabaNews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
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

        public HomeController(IPostService postService,
            IDataControlService dataControlService,
            IConfiguration configuration,
            IBannerService bannerService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
            this.configuration = configuration;
            this.bannerService = bannerService;
        }

        public async Task<IActionResult> Index()
        {
            var viewModel = await dataControlService.GetHomePageViewModel();
            var banner = await bannerService.GetBannerInfoAsync();
            var domain = configuration.GetValue<string>("BaseUrl");
            var title = viewModel.Category.SEOTitle ??= viewModel.Category.Title;
            var header = new HeaderViewModel
            {
                CategoryId = Constants.Home,
                CategorySlug = Constants.Empty,
                Title = title,
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
            viewModel.Paging.Href = Url.Action("Category", "Home", new { slug, page = (int?)null });
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
            if (viewModel == null)
            {
                return View(Constants.EmptyBlogView);
            }
            viewModel.Paging.Href = Url.Action("Video", "Home", new { page = (int?)null });
            var view = (viewModel.Posts.Any()) ? Constants.VideoView : Constants.EmptyBlogView;
            return View(view, viewModel);
        }

        


        [Route("Search/{keyword}/page/{page}")]
        [Route("Search/{keyword}")]
        public async Task<IActionResult> Search(string keyword, int page = 1)
        {
            keyword ??= "";
            var start = (page - 1) * Constants.Perpage;
            var criteria = new SearchPostsCriteria { _Keyword = keyword, _Start = start, _End = Constants.Perpage };
            var posts = await postService.SearchPostAsync(criteria);
            var totalItems = criteria.TotalItems;
            var model = new CategoryViewModel
            {
                Posts = posts,
                Header = new HeaderViewModel
                {
                    Title = Constants.SearchTitle + " '" + keyword + "'",
                    HeadTitle = Constants.SearchTitle + " '" + keyword + "'",
                    Description = Constants.SearchTitle + " '" + keyword + "'",
                    Keyword = keyword,
                    Controller = Constants.Search
                },
                Paging = new PagingModel
                {
                    CurrentPage = page,
                    PageSize = Constants.Perpage,
                    TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage),
                    Href = Url.Action("Search", "Home", new { keyword, page = (int?)null })
                }
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
            var model = new CategoryViewModel
            {
                Posts = posts,
                Category = new Category
                {
                    OgTitle = string.Concat(tag, " | ", Constants.SiteName),
                    OgImage = string.Concat(configuration.GetValue<string>("BaseUrl"), "images/sabasports.svg"),
                    Url = Url.Action("Tag", "Home", new { tagname }, Request.Scheme)
                },
                Header = new HeaderViewModel
                {
                    Title = string.Concat(tag, " | ", Constants.SiteName),
                    HeadTitle = "Tag: '" + tag + "'",
                    Keyword = tagname,
                    Controller = Constants.Tag
                },
                Paging = new PagingModel
                {
                    CurrentPage = page,
                    PageSize = Constants.Perpage,
                    TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage),
                    Href = Url.Action("Tag", "Home", new { tagname, page = (int?)null })
                }
            };

            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, model);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetailBySlugAsync(Constants.MatchScheduleSlug, Constants.pageType);
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.MatchScheduleSlug);
            var title = pageDetail.SEOTitle != null ? pageDetail.SEOTitle.Replace(Constants.CurrentDateVar, DateTime.UtcNow.ToString("dd/MM/yyyy", CultureInfo.InvariantCulture)) : pageDetail.Title;
            var description = pageDetail.Description ??= title;
            var model = new PageViewModel
            {
                Post = post,
                Category = pageDetail,
                Header = new HeaderViewModel
                {
                    CategorySlug = Constants.MatchScheduleSlug,
                    CategoryId = pageDetail.CatId,
                    Title = title,
                    Description = description
                }
            };

            return View(model);
        }

        [Route("warning")]
        public async Task<IActionResult> Warning()
        {
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.WarningSlug);
            var title = pageDetail.SEOTitle ?? pageDetail.Title;
            var description = pageDetail.Description ??= title;
            var model = new PageViewModel
            {
                Category = pageDetail,
                Header = new HeaderViewModel
                {
                    CategorySlug = Constants.WarningSlug,
                    CategoryId = pageDetail.CatId,
                    Title = title,
                    Description = description
                }
            };

            return View(model);
        }

        [Route("thong-bao-thay-doi-ten-mien")]
        public ActionResult ChangeDomain()
        {
            return View();
        }
    }
}