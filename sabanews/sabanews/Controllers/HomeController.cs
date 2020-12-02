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
                NewPosts = await postService.GetPostsAsync(Constants.NewPostCategory, 0, 4),
                FootballPosts = await postService.GetPostsAsync(Constants.FootballCategory, 0, 8),
                BasketballPosts = await postService.GetPostsAsync(Constants.BasketballCategory, 0, 6),
                TennisPosts = await postService.GetPostsAsync(Constants.TennisCategory, 0, 5),
                VideoPosts = await postService.GetPostsAsync(Constants.VideoCategory, 0, 6),
                GuessPosts = await postService.GetPostsAsync(Constants.GuessCategory, 0, 3),
                BackstagePosts = await postService.GetPostsAsync(Constants.BackstageCategory, 0, 3),
                TransferPosts = await postService.GetPostsAsync(Constants.TransferCategory, 0, 5)
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
            ViewData["TotalPages"] = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.Perpage);
            ViewData["CategoryId"] = cate.CatId;
            ViewData["Controller"] = "category";
            var view = parent == "video" ? "Video" : "Blog";
            view = posts.Count() == 0 ? "EmptyBlog" : view;
            return View(view, posts);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetailAsync(Constants.MatchScheduleCategory);
            ViewData["CategoryId"] = Constants.MatchScheduleCategory;
            ViewData["Title"] = "Lịch thi đấu hôm nay "+ DateTime.Now + " giải ngoại hạng Anh, Tây Ban Nha, C1, Serie A, NBA, ...";
            return View(post);
        }

        [Route("single/{ID}/{slug?}")]
        public async Task<IActionResult> Single(int ID)
        {
            var post = await postService.GetPostDetailAsync(ID);
            var relatedPosts = await postService.GetRelatedPostAsync(ID);
            var randomRelatedPosts = await postService.GetRandomReplatedPostAsync(post.Categories, ID);
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
            var Posts = await postService.SearchPostAsync(keyword, start, Constants.Perpage);
            var PostsTotal = await postService.SearchPostAsync(keyword, 0, Constants.Total);
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
            var Posts = await postService.GetPostByTagAsync(tag, start, Constants.Perpage);
            var PostsTotal = await postService.GetPostByTagAsync(tag, 0, Constants.Total);
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
            var Posts = await postService.GetPostByDateAsync(date, start, Constants.Perpage);
            var PostsTotal = await postService.GetPostByDateAsync(date, 0, Constants.Total);
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
