using Microsoft.AspNetCore.Mvc;
using SabaNews.Core.Constants;
using SabaNews.Models;
using SabaNews.Models.ViewModel;
using SabaNews.Services.Interfaces;
using System.Linq;
using System.Threading.Tasks;

namespace SabaNews.Site.Controllers
{
    public class Euro2020Controller : Controller
    {
        private readonly IPostService postService;

        public Euro2020Controller(
            IPostService postService)
        {
            this.postService = postService;
        }
        [Route("/euro-2020/")]
        public async Task<IActionResult> Index()
        {
            var postParam = new PostParam
            {
                Start = 0,
                End = Constants.Total,
                Slug = Constants.Euro2020Slug
            };
            var postTemplate = await postService.GetPostsByCateSlugAsync(postParam);
            var cate = await postService.GetCateDetailAsync(Constants.Euro2020Slug);
            var viewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = cate.SEOTitle ??= cate.Title,
                Description = cate.Description
            };
            var Model = new CategoryViewModel
            {
                Posts = postTemplate.Posts,
                Category = cate,
                ViewData = viewData
            };
            ViewData[Constants.ViewDataCategorySlug] = Constants.Euro2020Slug;
            return View(Model);
        }

        [Route("/euro-2020/doi-tuyen-euro-2020")]
        public async Task<IActionResult> Team()
        {
            var postParam = new PostParam
            {
                Start = 0,
                End = Constants.Total,
                Slug = Constants.Euro2020TeamSlug
            };
            var postTemplate = await postService.GetPostsByCateSlugAsync(postParam);
            var posts = postTemplate.Posts.OrderBy(item => item.PostSeoTitle);
            var cate = await postService.GetCateDetailAsync(Constants.Euro2020TeamSlug);
            var viewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = cate.SEOTitle ??= cate.Title,
                Description = cate.Description
            };
            var Model = new CategoryViewModel
            {
                Posts = posts,
                Category = cate,
                ViewData = viewData
                
            };

            ViewData[Constants.ViewDataCategorySlug] = Constants.Euro2020Slug;
            ViewData[Constants.ViewDataActiveSubCate] = Constants.Euro2020TeamSlug;
            return View(Model);
        }

        [Route("/euro-2020/video-euro-2020")]
        public async Task<IActionResult> Video()
        {
            var postParam = new PostParam
            {
                Start = 0,
                End = Constants.Total,
                Slug = Constants.Euro2020VideoSlug
            };
            var postTemplate = await postService.GetPostsByCateSlugAsync(postParam);
            var cate = await postService.GetCateDetailAsync(Constants.Euro2020VideoSlug);
            var viewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = cate.SEOTitle ??= cate.Title,
                Description = cate.Description
            };
            var Model = new CategoryViewModel
            {
                Posts = postTemplate.Posts,
                Category = cate,
                ViewData = viewData

            };
            ViewData[Constants.ViewDataCategorySlug] = Constants.Euro2020Slug;
            ViewData[Constants.ViewDataActiveSubCate] = Constants.Euro2020VideoSlug;
            return View(Model);
        }

        [Route("/euro-2020/lich-thi-dau-euro-2020")]
        public async Task<IActionResult> Schedule()
        {
            var postParam = new PostParam
            {
                Start = 0,
                End = Constants.Total,
                Slug = Constants.Euro2020ScheduleSlug
            };
            var postTemplate = await postService.GetPostsByCateSlugAsync(postParam);
            var posts = postTemplate.Posts.Where(item => item.PostSeoTitle.Trim().ToLower() != Constants.TemplatePost).ToList();
            var cate = await postService.GetCateDetailAsync(Constants.Euro2020ScheduleSlug);
            var viewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = cate.SEOTitle ??= cate.Title,
                Description = cate.Description
            };
            var Model = new CategoryViewModel
            {
                Posts = posts,
                Category = cate,
                ViewData = viewData

            };
            ViewData[Constants.ViewDataCategorySlug] = Constants.Euro2020Slug;
            ViewData[Constants.ViewDataActiveSubCate] = Constants.Euro2020ScheduleSlug;
            return View(Model);
        }

        [Route("/euro-2020/bang-xep-hang-euro-2020")]
        public async Task<IActionResult> Ranking()
        {
            var postParam = new PostParam
            {
                Start = 0,
                End = Constants.Total,
                Slug = Constants.Euro2020RankingSlug
            };
            var postTemplate = await postService.GetPostsByCateSlugAsync(postParam);
            var posts = postTemplate.Posts.Where(item => item.PostSeoTitle.Trim().ToLower() != Constants.TemplatePost).ToList();
            var cate = await postService.GetCateDetailAsync(Constants.Euro2020RankingSlug);
            var viewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = cate.SEOTitle ??= cate.Title,
                Description = cate.Description
            };
            var Model = new CategoryViewModel
            {
                Posts = posts,
                Category = cate,
                ViewData = viewData

            };
            ViewData[Constants.ViewDataCategorySlug] = Constants.Euro2020Slug;
            ViewData[Constants.ViewDataActiveSubCate] = Constants.Euro2020RankingSlug;
            return View(Model);
        }

        [Route("/euro-2020/getTabData")]
        public async Task<IActionResult> GetTabData(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            var isSuccessful = true;
            if (post == null)
            {
                isSuccessful = false;
            }
            return Json(new { isSuccessful, data = post });
        }
    }
}
