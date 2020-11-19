namespace sabanews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Constants;
    using sabanews.Models;
    using sabanews.Services;
    using System;
    using System.Diagnostics;
    using System.Linq;
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
                NewPosts = await postService.GetAsync(new PostParam { Category = Constants.NewPostCategory, Start = 0, End = 5 }),
                FootballPosts = await postService.GetAsync(new PostParam { Category = Constants.FootballCategory, Start = 0, End = 5 }),
                BasketballPosts = await postService.GetAsync(new PostParam { Category = Constants.BasketballCategory, Start = 0, End = 3 }),
                TennisPosts = await postService.GetAsync(new PostParam { Category = Constants.TennisCategory, Start = 0, End = 3 }),
                VideoPosts = await postService.GetAsync(new PostParam { Category = Constants.VideoCategory, Start = 0, End = 6 }),
                GuessPosts = await postService.GetAsync(new PostParam { Category = Constants.GuessCategory, Start = 0, End = 3 }),
                BackstagePosts = await postService.GetAsync(new PostParam { Category = Constants.BackstageCategory, Start = 0, End = 3 }),
                TransferPosts = await postService.GetAsync(new PostParam { Category = Constants.TransferCategory, Start = 0, End = 5 })
            };

            return View(Models);
        }

        [Route("category/{cateId}/{page}")]
        public async Task<IActionResult> Category(int cateId, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.GetAsync(new PostParam { Category = cateId, Start = start, End =  Constants.Perpage });
            var PostsTotal = await postService.GetAsync(new PostParam { Category = cateId, Start = 0, End = Constants.Total });
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["CategoryId"] = cateId;
            ViewData["controller"] = "category";
            return View("Blog",Posts);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetail(Constants.MatchScheduleCategory);
            ViewData["CategoryId"] = Constants.MatchScheduleCategory;
            return View(post);
        }

        [Route("single/{ID}")]
        public async Task<IActionResult> Single(int ID)
        {
            var post = await postService.GetPostDetail(ID);
            var relatedPosts = await postService.GetRelatedPost(ID, post.Tags);
            var randomRelatedPosts = await postService.GetRandomReplatedPost(post.Categories, ID);
            var Models = new SingleViewModel
            {
                SinglePost = post,
                RelatedPosts = relatedPosts,
                RandomRelatedPosts = randomRelatedPosts
            };
            ViewData["BaseUrl"] = $"{this.Request.Scheme}://{this.Request.Host}{this.Request.PathBase}";

            return View(Models);
        }

        [Route("Search")]
        public async Task<IActionResult> Search(string keyword, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.SearchPost(keyword, start, Constants.Perpage);
            var PostsTotal = await postService.SearchPost(keyword, 0, Constants.Total);
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["keyword"] = keyword;
            ViewData["controller"] = "Search";
            return View("Blog", Posts);
        }


        [Route("tag/{tag}")]
        public async Task<IActionResult> Tag(string tag, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.GetPostByTag(tag, start, Constants.Perpage);
            var PostsTotal = await postService.GetPostByTag(tag, 0, Constants.Total);
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["tag"] = tag;
            ViewData["controller"] = "Tag";
            return View("Blog", Posts);
        }

        public IActionResult IndexVC()
        {
            return ViewComponent("PriorityList", new { maxPriority = 3, isDone = false });
        }

        [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
        public IActionResult Error()
        {
            return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
        }
    }
}
