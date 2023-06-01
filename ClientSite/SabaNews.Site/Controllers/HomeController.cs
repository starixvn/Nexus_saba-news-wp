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
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;

    public class HomeController : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly IUserService userService;

        public HomeController(IPostService postService,
            IDataControlService dataControlService,
            IConfiguration configuration,
            IUserService userService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
            this.configuration = configuration;
            this.userService = userService;
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

        [Route("/{slug:categoryslug}")]
        public async Task<IActionResult> Category(string slug)
        {
            slug = slug.Replace(CategoryKey.Suffix, "");
            var viewModel = await dataControlService.GetCategoryViewModel(slug);
            if (viewModel == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var menus = await dataControlService.GetMenuData();
            var slugs = PostHelper.GetCategorySlugs(menus.ToList(), slug);
            viewModel.Header.ActiveCategory = slugs.Slug1 ?? slug;
            viewModel.Menus = menus;
            viewModel.Slugs = slugs;
            var view = viewModel.IsVideo ? Constants.VideoView : Constants.BlogView;
            return View(view, viewModel);
        }

        [Route("/tin-the-thao/{slug1?}/{slug2?}/{slug3?}")]
        public ActionResult OldCategorys(string slug1 = null, string slug2 = null, string slug3 = null)
        {
            string slug;
            if (slug3 != null)
            {
                slug = slug3;
            }
            else if(slug2 != null)
            {
                slug = slug2;
            }
            else if(slug1 != null )
            {
                slug = slug1;
            }
            else
            {
                slug = Constants.NewPostSlug;
            }
            return RedirectToAction("Category", "Home", new { slug = slug + CategoryKey.Suffix });
        }

        [Route("/tin-the-thao/{slug?}/page/{page}")]
        [Route("/tin-the-thao/page/{page}")]
        public IActionResult OldCategory(string slug = null, int page = 1)
        {
            if (slug == Constants.NewPostSlug)
            {
                slug = null;
            }
            return RedirectToAction("Category", "Home", new { slug });
        }

        [Route("/video/{slug?}")]
        [Route("/video/page/{page}")]
        public IActionResult OldVideo(int page = 1)
        {
            return RedirectToAction("Category", "Home", new { slug = Constants.VideoSlug + CategoryKey.Suffix });
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
                    HeadTitle = tag,
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

        [Route("author/{authorId}")]
        public async Task<IActionResult> Author(int authorId, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var criteria = new GetPostsByAuthorCriteria { AuthorId = authorId, Start = start, End = Constants.Perpage };
            var user = await userService.GetUserByIdAsync(authorId);
            var posts = await postService.GetPostsByAuthor(criteria);
            var totalItems = criteria.TotalItems;
            var authorName = user != null ? user.Name : "";
            var model = new BlogViewModel
            {
                Posts = posts.ToList(),
                Category = new Category
                {
                    OgTitle = string.Concat(authorName, " | ", Constants.SiteName),
                    OgImage = string.Concat(configuration.GetValue<string>("BaseUrl"), "images/sabasports.svg"),
                    Url = Url.Action("Author", "Home", new { authorId }, Request.Scheme)
                },
                Header = new HeaderViewModel
                {
                    Title = string.Concat(authorName, " | ", Constants.SiteName),
                    HeadTitle = "Tác giả: '" + authorName + "'",
                    Controller = Constants.Author,
                    Keyword = authorId.ToString()
                },
                Paging = new PagingModel
                {
                    CurrentPage = page,
                    PageSize = Constants.Perpage,
                    TotalPages = (int)Math.Ceiling(totalItems / (double)Constants.Perpage),
                    Href = Url.Action("Author", "Home", new { authorId, page = (int?)null }, Request.Scheme)
                }
            };

            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, model);
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

        [Route("policy")]
        public ActionResult Policy()
        {

            return View();
        }


        [HttpPost]
        [Route("api/post/category-posts")]
        public async Task<IActionResult> GetCategoryGrid(CategoryPostParams param)
        {
            var slug = param.Slug ??= Constants.NewPostSlug;
            int pageSize = param.PageSize;
            var start = (param.Page - 1) * pageSize;
            if (param.IsVideo)
            {
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
                var criteria = new GetPostsByCateSlugPagingCriteria
                {
                   _CategorySlug = slug,
                    _Start = start,
                    _End = pageSize
                };
                posts = await postService.GetPostsByCateSlugPagingAsync(criteria);
            }
            if (!posts.Any())
            {
                return Ok(false);
            }
            if(param.Page == Constants.SecondPage && param.FirstPagePosts != null)
            {
                posts = posts.Where(item => param.FirstPagePosts.All(id => id != item.ID));
            }
            var view = param.IsVideo ? "~/Views/Home/_VideoGridView.cshtml" : "~/Views/Home/_CategoryGridView.cshtml";
            ViewBag.CurrentPage = param.Page;
            return PartialView(view, posts);
        }

        [HttpPost]
        [Route("api/post/type-posts")]
        public async Task<IActionResult> GetTypePostsGrid(TypePostParam param)
        {
            var start = (param.Page - 1) * param.PageSize;
            IEnumerable<Post> posts = new List<Post>();
            if(param.Type == Constants.Search)
            {
                posts = await postService.SearchPostAsync(new SearchPostsCriteria { _Keyword = param.Keyword, _Start = start, _End = param.PageSize });
            }
            else if(param.Type == Constants.Tag)
            {
                posts = await postService.GetPostByTagAsync(new GetPostsByTagCriteria { _Tag = param.Keyword, _Start = start, _End = param.PageSize });
            }
            else if(param.Type == Constants.Author)
            {
                posts = await postService.GetPostsByAuthor(new GetPostsByAuthorCriteria { AuthorId = long.Parse(param.Keyword), Start = start, End = param.PageSize });
            } else {
                return Ok(false);
            }
            if (!posts.Any())
            {
                return Ok(false);
            }
            return PartialView("~/Views/Home/_CategoryGridView.cshtml", posts);
        }

        [HttpPost]
        [Route("api/GetHomeFixture")]
        public async Task<IActionResult> GetHomeFixture()
        {
            var fixture = await dataControlService.GetHomepageFixtureData();
            return Json(fixture);
        }

        [HttpPost]
        [Route("api/GetHomeStanding")]
        public async Task<IActionResult> GetHomeStanding()
        {
            var fixture = await dataControlService.GetHomepageStandingData();
            return Json(fixture);
        }
    }
}