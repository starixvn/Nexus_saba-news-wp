namespace SabaNews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Globalization;
    using System.Linq;
    using System.Security.Cryptography;
    using System.Text;
    using System.Threading.Tasks;

    public class HomeController : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IProfileService profileService;

        public HomeController(IPostService postService, 
            IDataControlService dataControlService, 
            IConfiguration configuration, 
            ISessionService sessionService,
            IProfileService profileService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.profileService = profileService;
        }

        public async Task<IActionResult> Index()
        {
            var viewModel = await dataControlService.GetHomePageViewModel();

            ViewData[Constants.ViewDataCategoryId] = Constants.Home;
            ViewData[Constants.ViewDataCategorySlug] = Constants.Empty;
            ViewData[Constants.ViewDataTitle] = viewModel.Category.SEOTitle ??= viewModel.Category.Title;
            ViewData[Constants.ViewDataDescription] = viewModel.Category.Description;
            var domain = configuration.GetValue<string>("SitemapSettings:Domain");
            ViewData[Constants.ViewUrl] = domain;

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
            var Model = new PageViewModel
            {
                Post = post,
                Category = pageDetail
            };
            ViewData[Constants.ViewDataCategorySlug] = Constants.MatchScheduleSlug;
            ViewData[Constants.ViewDataCategoryId] = pageDetail.CatId;
            ViewData[Constants.ViewDataTitle] = title;
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= title;
            return View(Model);
        }

        [Route("warning")]
        public async Task<IActionResult> Warning()
        {
            var pageDetail = await postService.GetPageDetailBySlugAsync(Constants.WarningSlug);
            var title = pageDetail.SEOTitle ?? pageDetail.Title;
            var Model = new PageViewModel
            {
                Category = pageDetail
            };
            ViewData[Constants.ViewDataCategorySlug] = Constants.WarningSlug;
            ViewData[Constants.ViewDataCategoryId] = pageDetail.CatId;
            ViewData[Constants.ViewDataTitle] = title;
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= title;
            return View(Model);
        }


        [Route("{slug}")]
        public async Task<IActionResult> Single(string slug)
        {

            var hash = GetMd5Hash($"https://vn.sabasports.com/{slug}");
            var post = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            if (IsBlackListIP())
            {
                post = await GetRandomPost(post);
                post.PostContent += $"\n Nguồn: <a href='https://vn.sabasports.com/{post.Slug}'>vn.sabasports.com</a>";
            }

            if (post == null)
            {
                return View(Constants.EmptyBlogView);
            }
            
            post.PostContent = post.PostContent.Replace("\"rank-math-link\"", $"\"rank-math-link\" data-pro=\"{hash}\"");
            post.PostContent = post.PostContent.Replace("'rank-math-link'", $"'rank-math-link' data-pro=\"{hash}\"");

            var subString = post.PostContent.Split("<p>");
            if (subString.Count() > 1)
            {
                Random random = new Random();
                var randomIndex = random.Next(1, subString.Count() - 1);
                var oldText = $"<p>{subString[randomIndex]}";
                var newText = $"<p data-z=\"{hash}\">{subString[randomIndex]}";
                post.PostContent = post.PostContent.Replace(oldText, newText);
            }

            var recentPosts = await postService.GetRecentPostsAsync(Constants.VideoSlug, Constants.FromFirstItem, Constants.GetElevenItems, post.ID);
            var cateSlug = post.CategoriesSlug;
            var isSave = false;
            var isLogin = sessionService.User != null;
            if (isLogin)
            {
                var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
                var savePost = user.HandleSavePosts.Find(val => val.PostID == post.ID);
                if (savePost != null)
                {
                    isSave = true;
                }
            }
            if (cateSlug.Contains(","))
            {
                var listCateSlug = post.CategoriesSlug.Split(',');
                var exceptCateSlug = listCateSlug.Where(item => item != Constants.FootballSlug && item != Constants.VideoSlug).ToList() ;
                cateSlug = exceptCateSlug[0] ?? cateSlug;
            }
            var Models = new SingleViewModel
            {
                SinglePost = post,
                RecentPosts = recentPosts.Posts,
                IsLogin = Constants.IsLogin,
                isSave = isSave
            };
            var domain = configuration.GetValue<string>("SitemapSettings:Domain");
            ViewData[Constants.ViewUrl] = $"{domain}{slug}";
            ViewData["BaseUrl"] = $"{this.Request.Scheme}://{this.Request.Host}{this.Request.PathBase}";
            ViewData["video"] = post.Type == Constants.VideoPostType;
            ViewData["cateSlug"] = cateSlug;

            return View(Models);
        }
        public static string GetMd5Hash(string input)
        { // Create a new instance of the MD5CryptoServiceProvider object.
            MD5 md5Hasher = MD5.Create(); // Convert the input string to a byte array and compute the hash.
            byte[] data = md5Hasher.ComputeHash(Encoding.Default.GetBytes(input));
            // Create a new Stringbuilder to collect the bytes // and create a string.
            StringBuilder sBuilder = new StringBuilder(); // Loop through each byte of the hashed data // and format each one as a hexadecimal string.
            for (int i = 0; i < data.Length; i++)
            {
                sBuilder.Append(data[i].ToString("x2"));
            }
            // Return the hexadecimal string.
            return sBuilder.ToString();
        }

        private async Task<Post> GetRandomPost(Post post)
        {
            var categoryPlus = post.CategoriesSlug.Split(",").FirstOrDefault();

            var relatedPosts = await postService.GetPostsBySlugAsync(categoryPlus, 10, 1000);
            Random random = new Random();
            var postIndexRandom = random.Next(0, relatedPosts.Count());
            var newPost = relatedPosts.ToList()[postIndexRandom];
            newPost.PostDateGMT = post.PostDateGMT;
            newPost.PostDate = post.PostDate;
            return newPost;
        }

        private bool IsBlackListIP()
        {
            var clientIP = HttpContext.Request.Headers["x-forwarded-for"].ToString();
            if (string.IsNullOrWhiteSpace(clientIP))
            {
                clientIP = HttpContext.Connection.RemoteIpAddress.ToString();
            }
            var blackListIPs = postService.GetBlackListIPs();

            foreach (var blackListIP in blackListIPs)
            {
                if (clientIP.Contains(blackListIP))
                {
                    return true;
                }
            }

            return false;
        }

        public static string MD5Hash(string input)
        {
            using (var md5 = MD5.Create())
            {
                var result = md5.ComputeHash(Encoding.ASCII.GetBytes(input));
                return Encoding.ASCII.GetString(result);
            }
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