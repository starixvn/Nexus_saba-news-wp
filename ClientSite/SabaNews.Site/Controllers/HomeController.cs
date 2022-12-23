namespace SabaNews.Controllers
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Net;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Posts;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;

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
            return View(viewModel);
        }

        [Route("/tin-the-thao/{slug1?}/{slug2?}/{slug3?}")]
        public async Task<IActionResult> Category(string slug1 = null, string slug2 = null, string slug3 = null)
        {
            var param = new GetPostsByCateSlugsParam
            {
                Slug1 = slug1,
                Slug2 = slug2,
                Slug3 = slug3,
                Page = Constants.FirstPage
              
            };
            var viewModel = await dataControlService.GetCategoryViewModel(param);
            if (viewModel == null)
            {
                return View(Constants.EmptyBlogView);
            }

            viewModel.Header.ActiveCategory = slug1;
            viewModel.Paging.Href = Url.Action("Category", "Home", new { slug1, page = (int?)null });
            viewModel.Menus = await dataControlService.GetMenuData();
            return View(Constants.BlogView, viewModel);
        }

        [Route("/tin-the-thao/{slug?}/page/{page}")]
        [Route("/tin-the-thao/page/{page}")]
        public IActionResult OldCategory(string slug = null, int page = 1)
        {
            if (slug == Constants.NewPostSlug)
            {
                slug = null;
            }
            return RedirectToAction("Category", "Home", new { slug1 = slug });
            
        }

        [Route("/video/{slug2?}/{slug3?}")]
        public async Task<IActionResult> Video( string slug2 = null, string slug3 = null)
        {
            var viewModel = await dataControlService.GetCategoryViewModel(new GetPostsByCateSlugsParam { Slug1 = Constants.VideoSlug, Slug2 = slug2, Slug3 = slug3, Page = Constants.FirstPage });
            if (viewModel == null)
            {
                return View(Constants.EmptyBlogView);
            }
            viewModel.Menus = await dataControlService.GetMenuData();
            var view = (viewModel.Posts.Any()) ? Constants.VideoView : Constants.EmptyBlogView;
            return View(view, viewModel);
        }

        [Route("/video/page/{page}")]
        public IActionResult OldVideo(int page = 1)
        {
            return RedirectToAction("Video", "Home");
        }

        [Route("Search/{keyword}")]
        public async Task<IActionResult> Search(string keyword, int page = 1)
        {
            keyword ??= "";
            var start = (page - 1) * Constants.Perpage;
            var criteria = new SearchPostsCriteria { _Keyword = keyword, _Start = start, _End = Constants.Perpage };
            var posts = await postService.SearchPostAsync(criteria);
            var totalItems = criteria.TotalItems;
            var model = new BlogViewModel
            {
                Posts = posts.ToList(),
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

        [Route("/Search/page/{page}")]
        [Route("/Search/")]
        [Route("Search/{keyword}/page/{page}")]
        public IActionResult OldSearch(string keyword = "")
        {
            keyword = WebUtility.UrlEncode(keyword);
            return RedirectToAction("Search", "Home", new { keyword });

        }

        [Route("tag/{tagname}")]
        public async Task<IActionResult> Tag(string tagname, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var criteria = new GetPostsByTagCriteria { _Tag = tagname.Trim(), _Start = start, _End = Constants.Perpage };
            var posts = await postService.GetPostByTagAsync(criteria);
            var totalItems = criteria.TotalItems;
            var tag = posts.Any() ? posts.First().Tags : tagname;
            var model = new BlogViewModel
            {
                Posts = posts.ToList(),
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

        [Route("tag/{tagname}/page/{page}")]
        public IActionResult OldTag(string tagname, int page = 1)
        {
            return RedirectToAction("Tag", "Home", new { tagname });
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
                    CategoryId = pageDetail.CateId,
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

        [Route("lich-thi-dau")]
        public ActionResult RedirectLivescorePage()
        {
            return Redirect("/lich-thi-dau/bong-da");
        }

        [HttpPost]
        [Route("api/post/category-posts")]
        public async Task<IActionResult> GetCategoryGrid(CategoryPostParams param)
        {
            var slug = param.Slugs.Slug1 ??= Constants.NewPostSlug;
            int pageSize = param.PageSize;
            var start = (param.Page - 1) * pageSize;
            if (param.IsVideo) {
                start = param.Page > Constants.SecondPage ? (param.Page - 1) * pageSize + 1 : Constants.GetTenItems;
            }
            IEnumerable<Post> posts;
            if (slug == Constants.NewPostSlug)
            {
                var paramGetRecent = new GetRecentPostsParam
                {
                    Start = start,
                    End = pageSize
                };
                posts = await postService.GetRecentPostsAsync(paramGetRecent);
            }
            else
            {
                var criteria = new GetPostsByCateSlugsPagingCriteria
                {
                    Slug1 = slug,
                    Slug2 = param.Slugs.Slug2,
                    Slug3 = param.Slugs.Slug3,
                    Start = start,
                    End = pageSize
                };
                posts = await postService.GetPostsByCateSlugsPaging(criteria);
            }
            
            if (!posts.Any())
            {
                return Ok(false);
            }
            var view = param.IsVideo ? "~/Views/Home/_VideoGridView.cshtml" : "~/Views/Home/_CategoryGridView.cshtml";
            return PartialView(view, posts);
        }

        [HttpPost]
        [Route("api/post/type-posts")]
        public async Task<IActionResult> GetTypePostsGrid(TypePostParam param)
        {
            var start = (param.Page - 1) * param.PageSize;
            var posts = param.Type == Constants.Search
                        ? await postService.SearchPostAsync(new SearchPostsCriteria { _Keyword = param.Keyword, _Start = start, _End = param.PageSize })
                        : await postService.GetPostByTagAsync(new GetPostsByTagCriteria { _Tag = param.Keyword, _Start = start, _End = param.PageSize });
                                                 
            if (!posts.Any())
            {
                return Ok(false);
            }
            return PartialView("~/Views/Home/_CategoryGridView.cshtml", posts);
        }

        [HttpPost]
        [Route("api/match/get-matches-aff-cup")]
        public async Task<IActionResult> GetMatchesAFFCup()
        {
            (League league, IList<Match> matches) = await dataControlService.GetHomepageFixtureData();
            return Json(new { league, matches });
        }
    }
}