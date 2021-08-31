namespace SabaNews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Criteria;
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
            var viewData = new ViewData
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
            var model = new CategoryViewModel
            {
                Posts = posts,
                ViewData = viewData
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
            var description = "MỚI NHẤT: ";
            int i = 0;
            foreach (var post in posts)
            {
                i++;
                if (i == Constants.GetThreeItems)
                {
                    var titleArr = post.PostTitle.Split(' ');
                    var title = titleArr.Length > 2 ? titleArr[0] + " " + titleArr[1] + "..." : post.PostTitle;
                    description = string.Concat(description, title);
                }
                else if(i <= 2)
                {
                    description = string.Concat(description, post.PostTitle, "; ");
                }
            }
            var viewData = new ViewData
            {
                Title = string.Concat(tag, " | ", Constants.SiteName),
                HeadTitle = "Tag: '" + tag + "'",
                Description = description,
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(totalItems / (double )Constants.Perpage),
                Tag = tagname,
                Controller = Constants.Tag
            };
            var category = new Category
            {
                OgDescription = description,
                OgTitle = string.Concat(tag, " | ", Constants.SiteName),
                OgImage = string.Concat(configuration.GetValue<string>("BaseUrl"), "images/sabasports.svg"),
                Url = Url.Action("Tag", "Home", new { tagname }, Request.Scheme)
            };
            var model = new CategoryViewModel
            {
                Posts = posts,
                ViewData = viewData,
                Category = category
            };

            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, model);
        }
    }
}