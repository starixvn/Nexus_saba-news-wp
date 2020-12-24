namespace sabanews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Constants;
    using sabanews.Models;
    using sabanews.Services.Interfaces;
    using System;
    using System.Linq;
    using System.Text;
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
                NewPosts = await postService.GetPostsAsync(Constants.NewPostCategory, Constants.FromFirstItem, Constants.GetFourItems),
                FootballPosts = await postService.GetPostsAsync(Constants.FootballCategory, Constants.FromFirstItem, Constants.GetEightItems),
                BasketballPosts = await postService.GetPostsAsync(Constants.BasketballCategory, Constants.FromFirstItem, Constants.GetSixItems),
                TennisPosts = await postService.GetPostsAsync(Constants.TennisCategory, Constants.FromFirstItem, Constants.GetFiveItems),
                VideoPosts = await postService.GetPostsAsync(Constants.VideoCategory, Constants.FromFirstItem, Constants.GetSixItems),
                GuessPosts = await postService.GetPostsAsync(Constants.GuessCategory, Constants.FromFirstItem, Constants.GetThreeItems),
                BackstagePosts = await postService.GetPostsAsync(Constants.BackstageCategory, Constants.FromFirstItem, Constants.GetThreeItems),
                TransferPosts = await postService.GetPostsAsync(Constants.TransferCategory, Constants.FromFirstItem, Constants.GetFiveItems)
            };
            var pageDetail = await postService.GetPageDetailAsync(Constants.Home);
            ViewData["CategoryId"] = Constants.Home;
            ViewData["Title"] = pageDetail.Title;
            ViewData["description"] = pageDetail.Description ??= pageDetail.Title;
            return View(Models);
        }

        [Route("/tin-tuc/{slug?}/page/{page}")]
        [Route("/tin-tuc/{slug?}")]
        public async Task<IActionResult> Category(string slug = null, int page = 1)
        {
            var perpage = Constants.Perpage;
            var start = (page - 1) * perpage;
            slug ??= Constants.NewPostSlug;
            var cate = await postService.GetCateDetailAsync(slug);
            var posts = await postService.GetPostsBySlugAsync(slug, start, perpage);
            var postsTotal = await postService.GetPostsBySlugAsync(slug, 0, Constants.Total);
            ViewData["Title"] = cate.Title;
            ViewData["description"] = cate.Description ??= cate.Title;
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(postsTotal.Count() / (double)perpage);
            ViewData["CategoryId"] = cate.CatId;
            ViewData["Controller"] = "category";
            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, posts);
        }

        [Route("/video/{slug?}/page/{page}")]
        [Route("/video/{slug?}")]
        public async Task<IActionResult> Video(string slug = null, int page = 1)
        {
            var perpage = Constants.PerpageVideo;
            var start = (page - 1) * perpage;
            slug ??= Constants.VideoSlug;
            var cate = await postService.GetCateDetailAsync(slug);
            var posts = await postService.GetPostsBySlugAsync(slug, start, perpage);
            var postsTotal = await postService.GetPostsBySlugAsync(slug, 0, Constants.Total);
            ViewData["Title"] = cate.Title;
            ViewData["description"] = cate.Description ??= cate.Title;
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(postsTotal.Count() / (double)perpage);
            ViewData["CategoryId"] = cate.CatId;
            ViewData["Controller"] = "video";
            var view = posts.Any() ? Constants.VideoView : Constants.EmptyBlogView;
            return View(view, posts);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetailAsync(Constants.MatchScheduleCategory);
            var pageDetail = await postService.GetPageDetailAsync(Constants.MatchScheduleCategory);
            ViewData["CategoryId"] = Constants.MatchScheduleCategory;
            ViewData["Title"] = pageDetail.Title.Replace("%currentdate%", DateTime.Now.ToString());
            ViewData["Description"] = pageDetail.Description ??= pageDetail.Title;
            return View(post);
        }

        [Route("{slug}")]
        public async Task<IActionResult> Single(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug);
            if (post == null) return View(Constants.EmptyBlogView);
            var relatedPosts = await postService.GetRelatedPostAsync(post.ID);
            var randomRelatedPosts = await postService.GetRandomReplatedPostAsync(post.Categories, post.ID);
            var Models = new SingleViewModel
            {
                SinglePost = post,
                RelatedPosts = relatedPosts,
                RandomRelatedPosts = randomRelatedPosts
            };
            ViewData["BaseUrl"] = $"{this.Request.Scheme}://{this.Request.Host}{this.Request.PathBase}";
            ViewData["video"] = post.Type == Constants.VideoPostType ? true : false;

            return View(Models);
        }

        [Route("Search/page/{page}")]
        [Route("Search")]
        public async Task<IActionResult> Search(string keyword, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.SearchPostAsync(keyword, start, Constants.Perpage);
            var PostsTotal = await postService.SearchPostAsync(keyword, 0, Constants.Total);
            ViewData["Title"] = Constants.SearchTitle + " '" + keyword + "'";
            ViewData["Description"] = ViewData["Title"];
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["keyword"] = keyword;
            ViewData["Controller"] = "Search";
            ViewData["HeadTitle"] = Constants.SearchTitle + " '" + keyword + "'";
            var view = Posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Posts);
        }

        [Route("tag/{tag}/page/{page}")]
        [Route("tag/{tag}")]
        public async Task<IActionResult> Tag(string tag, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.GetPostByTagAsync(tag, start, Constants.Perpage);
            var PostsTotal = await postService.GetPostByTagAsync(tag, 0, Constants.Total);
            ViewData["Title"] = await postService.GetTagNameBySlugAsync(tag);
            ViewData["Description"] = await postService.GetTagNameBySlugAsync(tag);
            ViewData["CurrentPage"] = page;
            ViewData["PageSize"] = Constants.Perpage;
            ViewData["TotalPages"] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData["tag"] = tag;
            ViewData["Controller"] = "Tag";
            ViewData["HeadTitle"] = "Tag: '" + tag + "'";
            var view = Posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Posts);
        }

        [Route("date/{date}/page/{page}")]
        [Route("date/{date}")]
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
            ViewData["HeadTitle"] = Constants.SearchTitle + " '" + date + "'";
            ViewData["Title"] = date + " - Saba Sports";
            var view = Posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Posts);
        }
    }
}