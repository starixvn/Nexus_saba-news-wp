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
                NewPosts = await postService.GetAsync(new PostParam { Category = Constants.NewPostCategory, Start = 0, End = 4 }),
                FootballPosts = await postService.GetAsync(new PostParam { Category = Constants.FootballCategory, Start = 0, End = 8 }),
                BasketballPosts = await postService.GetAsync(new PostParam { Category = Constants.BasketballCategory, Start = 0, End = 6 }),
                TennisPosts = await postService.GetAsync(new PostParam { Category = Constants.TennisCategory, Start = 0, End = 5 }),
                VideoPosts = await postService.GetAsync(new PostParam { Category = Constants.VideoCategory, Start = 0, End = 6 }),
                GuessPosts = await postService.GetAsync(new PostParam { Category = Constants.GuessCategory, Start = 0, End = 3 }),
                BackstagePosts = await postService.GetAsync(new PostParam { Category = Constants.BackstageCategory, Start = 0, End = 3 }),
                TransferPosts = await postService.GetAsync(new PostParam { Category = Constants.TransferCategory, Start = 0, End = 5 })
            };
            ViewData["CategoryId"] = Constants.Home;
            ViewData["Title"] = "Trang tin tức thể thao mới nhất, cập nhật liên tục";
            return View(Models);
        }

        [Route("category/{parent}/{slug?}/{page?}")]
        public async Task<IActionResult> Category(string parent,  string slug = null, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            slug = slug ?? parent;
            var cate = await postService.GetCateDetailAsync(slug);
            var posts = await postService.GetPostsBySlugAsync(slug, start, Constants.Perpage);
            var postsTotal = await postService.GetPostsBySlugAsync(slug, 0, Constants.Total);
            ViewData["Title"] = cate.Title;
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["CategoryId"] = cate.CatId;
            ViewData["Controller"] = "category";
            var view = parent == "video" ? "Video" : "Blog";
            view = Posts.Count() == 0 ? "EmptyBlog" : view;
            return View(view, Posts);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetail(Constants.MatchScheduleCategory);
            ViewData["CategoryId"] = Constants.MatchScheduleCategory;
            ViewData["Title"] = "Lịch thi đấu hôm nay "+ DateTime.Now + " giải ngoại hạng Anh, Tây Ban Nha, C1, Serie A, NBA, ...";
            return View(post);
        }

        [Route("single/{ID}/{slug?}")]
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
            ViewData["Title"] = "Kết quả tìm kiếm '" + keyword + "'";
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["keyword"] = keyword;
            ViewData["Controller"] = "Search";
            ViewData["HeadTitle"] = "Kết quả tìm kiếm từ khóa '" + keyword + "'";
            var view = Posts.Count() == 0 ? "EmptyBlog" : "Blog";
            return View(view, Posts);
        }


        [Route("tag/{tag}")]
        public async Task<IActionResult> Tag(string tag, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.GetPostByTag(tag, start, Constants.Perpage);
            var PostsTotal = await postService.GetPostByTag(tag, 0, Constants.Total);
            ViewData["Title"] = tag;
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["tag"] = tag;
            ViewData["Controller"] = "Tag";
            ViewData["HeadTitle"] = "Tag: '" + tag + "'";
            var view = Posts.Count() == 0 ? "EmptyBlog" : "Blog";
            return View(view, Posts);
        }

        [Route("{date}")]
        public async Task<IActionResult> DateSearch(string date, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.GetPostByDate(date, start, Constants.Perpage);
            var PostsTotal = await postService.GetPostByDate(date, 0, Constants.Total);
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["Date"] = date;
            ViewData["Controller"] = "DateSearch";
            ViewData["HeadTitle"] = "Kết quả tìm kiếm: '" + date + "'";
            ViewData["Title"] = date + " - Saba Sports";
            var view = Posts.Count() == 0 ? "EmptyBlog" : "Blog";
            return View(view, Posts);
        }

    }
}
