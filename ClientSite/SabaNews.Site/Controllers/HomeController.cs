namespace sabanews.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Constants;
    using sabanews.Models;
    using sabanews.Services.Interfaces;
    using System;
    using System.Globalization;
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
            ViewData[Constants.ViewDataCategoryId] = Constants.Home;
            ViewData[Constants.ViewDataTitle] = pageDetail.Title;
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= pageDetail.Title;
            return View(Models);
        }

        [Route("/tin-tuc/{slug?}/page/{page}")]
        [Route("/tin-tuc/{slug?}")]
        public async Task<IActionResult> Category(string slug = null, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            slug ??= Constants.NewPostSlug;
            var cate = await postService.GetCateDetailAsync(slug);
            var posts = await postService.GetPostsBySlugAsync(slug, start, Constants.Perpage);
            var postsTotal = await postService.GetPostsBySlugAsync(slug, 0, Constants.Total);
            ViewData[Constants.ViewDataTitle] = cate.Title;
            ViewData[Constants.ViewDataDescription] = cate.Description ??= cate.Title;
            ViewData[Constants.ViewDataCurrentPage] = page;
            ViewData[Constants.ViewDataPageSize] = Constants.Perpage;
            ViewData[Constants.ViewDataTotalPages] = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.Perpage);
            ViewData[Constants.ViewDataCategoryId] = cate.CatId;
            ViewData[Constants.ViewDataController] = "category";
            var view = posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, posts);
        }

        [Route("/video/{slug?}/page/{page}")]
        [Route("/video/{slug?}")]
        public async Task<IActionResult> Video(string slug = null, int page = 1)
        {
            var start = (page - 1) * Constants.PerpageVideo;
            slug ??= Constants.VideoSlug;
            var cate = await postService.GetCateDetailAsync(slug);
            var posts = await postService.GetPostsBySlugAsync(slug, start, Constants.PerpageVideo);
            var postsTotal = await postService.GetPostsBySlugAsync(slug, 0, Constants.Total);
            ViewData[Constants.ViewDataTitle] = cate.Title;
            ViewData[Constants.ViewDataDescription] = cate.Description ??= cate.Title;
            ViewData[Constants.ViewDataCurrentPage] = page;
            ViewData[Constants.ViewDataPageSize] = Constants.PerpageVideo;
            ViewData[Constants.ViewDataTotalPages] = (int)Math.Ceiling(postsTotal.Count() / (double)Constants.PerpageVideo);
            ViewData[Constants.ViewDataCategoryId] = cate.CatId;
            ViewData[Constants.ViewDataController] = "video";
            var view = posts.Any() ? Constants.VideoView : Constants.EmptyBlogView;
            return View(view, posts);
        }

        [Route("lich-thi-dau")]
        public async Task<IActionResult> MatchSchedule()
        {
            var post = await postService.GetPostDetailAsync(Constants.MatchScheduleCategory);
            var pageDetail = await postService.GetPageDetailAsync(Constants.MatchScheduleCategory);
            ViewData[Constants.ViewDataCategoryId] = Constants.MatchScheduleCategory;
            ViewData[Constants.ViewDataTitle] = pageDetail.Title.Replace("%currentdate%", DateTime.Now.ToString(CultureInfo.InvariantCulture));
            ViewData[Constants.ViewDataDescription] = pageDetail.Description ??= pageDetail.Title;
            return View(post);
        }

        [Route("{slug}")]
        public async Task<IActionResult> Single(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug);
            if (post == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var relatedPosts = await postService.GetRelatedPostAsync(post.ID);
            var randomRelatedPosts = await postService.GetRandomReplatedPostAsync(post.Categories, post.ID);
            var Models = new SingleViewModel
            {
                SinglePost = post,
                RelatedPosts = relatedPosts,
                RandomRelatedPosts = randomRelatedPosts
            };
            ViewData["BaseUrl"] = $"{this.Request.Scheme}://{this.Request.Host}{this.Request.PathBase}";
            ViewData["video"] = post.Type == Constants.VideoPostType;

            return View(Models);
        }

        [Route("Search/page/{page}")]
        [Route("Search")]
        public async Task<IActionResult> Search(string keyword, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.SearchPostAsync(keyword, start, Constants.Perpage);
            var PostsTotal = await postService.SearchPostAsync(keyword, 0, Constants.Total);
            ViewData[Constants.ViewDataTitle] = Constants.SearchTitle + " '" + keyword + "'";
            ViewData[Constants.ViewDataTitle] = ViewData["Title"];
            ViewData[Constants.ViewDataCurrentPage] = page;
            ViewData[Constants.ViewDataPageSize] = Constants.Perpage;
            ViewData[Constants.ViewDataTotalPages] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData[Constants.ViewDataKeyword] = keyword;
            ViewData[Constants.ViewDataController] = "Search";
            ViewData[Constants.ViewDataHeadTitle] = Constants.SearchTitle + " '" + keyword + "'";
            var view = Posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Posts);
        }

        [Route("tag/{tagname}/page/{page}")]
        [Route("tag/{tagname}")]
        public async Task<IActionResult> Tag(string tagname, int page = 1)
        {
            var start = (page - 1) * Constants.Perpage;
            var Posts = await postService.GetPostByTagAsync(tagname, start, Constants.Perpage);
            var PostsTotal = await postService.GetPostByTagAsync(tagname, 0, Constants.Total);
            ViewData[Constants.ViewDataTitle] = await postService.GetTagNameBySlugAsync(tagname);
            ViewData[Constants.ViewDataDescription] = await postService.GetTagNameBySlugAsync(tagname);
            ViewData[Constants.ViewDataCurrentPage] = page;
            ViewData[Constants.ViewDataPageSize] = Constants.Perpage;
            ViewData[Constants.ViewDataTotalPages] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData[Constants.ViewDataTag] = tagname;
            ViewData[Constants.ViewDataController] = "Tag";
            ViewData[Constants.ViewDataHeadTitle] = "Tag: '" + tagname + "'";
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
            ViewData[Constants.ViewDataCurrentPage] = page;
            ViewData[Constants.ViewDataPageSize] = Constants.Perpage;
            ViewData[Constants.ViewDataTotalPages] = (int)Math.Ceiling(PostsTotal.Count() / (double)Constants.Perpage);
            ViewData[Constants.ViewDataDate] = date;
            ViewData[Constants.ViewDataController] = "DateSearch";
            ViewData[Constants.ViewDataHeadTitle] = Constants.SearchTitle + " '" + date + "'";
            ViewData[Constants.ViewDataTitle] = date + " - Saba Sports";
            var view = Posts.Any() ? Constants.BlogView : Constants.EmptyBlogView;
            return View(view, Posts);
        }
    }
}