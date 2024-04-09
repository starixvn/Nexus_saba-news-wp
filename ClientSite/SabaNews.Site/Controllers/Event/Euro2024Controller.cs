using System;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using SabaNews.Core.Constants;
using SabaNews.Core.Models;
using SabaNews.Core.Services;
using SabaNews.Criteria.Posts;
using SabaNews.Models.Posts;
using SabaNews.Models.ViewModel;
using SabaNews.Models.ViewModel.Event.WorldCup;
using SabaNews.Models.ViewModel.Posts;
using SabaNews.Services;
using SabaNews.Services.Interfaces;
using SabaNews.Site.Extensions;

namespace SabaNews.Site.Controllers.Event
{
    public class Euro2024Controller : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IUserService userService;
        private const string CategorySlugEuro = Euro2024.HomepageSlug;
        public Euro2024Controller(
            IDataControlService dataControlService,
            IPostService postService,
            ISessionService sessionService,
            IConfiguration configuration,
            IUserService userService
        )
        {
            this.dataControlService = dataControlService;
            this.postService = postService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.userService = userService;
        }
        [Route("/euro-2024")]
        public async Task<IActionResult> Index ()
        {
            var model = await dataControlService.GetWorldCupPageViewModel();
            var header = new HeaderViewModel
            {
                HeadTitle = model.Category.Title,
                Title = Euro2024.TitleHomepage,
                Description = model.Category.Description,
                Slug = Euro2024.HomepageSlug
            };
            model.Header = header;
            return View("~/Views/Event/Euro2024/Index.cshtml", model);
        }
        [Route("/euro-2024/{slug}")]
        public async Task<IActionResult> PostDetailEuro (string slug)
        {
            var postDetail = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            if (postDetail == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var cateInfoWC = GetCateInfoEuro(postDetail.CatesName, postDetail.CategoriesSlug);

            var viewModel = await GetViewModelPostDetail(postDetail, cateInfoWC, slug);

            return View("~/Views/Event/Euro2024/PostDetail.cshtml", viewModel);
        }

        [Route("euro-2024/tin-moi-nhat")]
        public async Task<IActionResult> LastestNews ()
        {
            var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = CategorySlugEuro, _Start = 0, _End = Constants.GetTenItems };
            var news = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);

            var header = new HeaderViewModel
            {
                Title = WorldCup2022.TitleLastestNews,
                Description = WorldCup2022.TitleLastestNews,
                Slug = WorldCup2022.UrlLastestNewsSlug
            };

            var viewModel = new LastestNewsViewModel
            {
                Header = header,
                Posts = news,
                CategorySlug = CategorySlugEuro,
                Paging = new PagingModel
                {
                    PageSize = Constants.GetTenItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)Constants.GetTenItems)
                }
            };
            return View("~/Views/Event/Euro2024/LastestNewsEuro.cshtml" , viewModel);
        }


        private async Task<SingleViewModel> GetViewModelPostDetail(Post postWC, CateInfo cateInfoWC, string slug)
        {
            SavePost savePostWC = null;
            var isUserLogin = sessionService.User != null;
            if (isUserLogin)
            {
                var user = await userService.GetUserByIdAsync(sessionService.User.Id);
                savePostWC = user.HandleSavePosts.Find(val => val.PostID == postWC.ID);
            }
            var cateParentsWC = await postService.GetCateParentsAsync(cateInfoWC.Slug);
            cateInfoWC.Url ??= PostHelper.GetCategoryLink(cateParentsWC);
            var domain = configuration.GetValue<string>(Constants.BaseUrl);
            var viewModel = new SingleViewModel
            {
                SinglePost = postWC,
                IsLogin = isUserLogin,
                SavePost = savePostWC,
                AbsoluteUrl = $"{domain}{slug}",
                IsVideo = PostHelper.IsVideo(cateParentsWC),
                CateInfo = cateInfoWC
            };
            return viewModel;
        }

        private static CateInfo GetCateInfoEuro(string cateName, string cateSlugWC)
        {
            var slugWC = cateSlugWC;
            var cateNameWC = cateName;
            var cateEventWC = GetEventEuroInfo(cateSlugWC);
            if (cateEventWC != null)
            {
                return cateEventWC;
            }
            if (cateSlugWC.Contains(Constants.CollectionSlug) || (cateSlugWC.Contains(Constants.BasketballSlug)
                && !cateSlugWC.Contains(Constants.BasketballVideoSlug)) || cateSlugWC.Contains(Constants.TennisSlug))
            {
                return new CateInfo { Slug = Constants.OtherPostSlug, Name = Constants.OtherPosts };
            }

            var listCateSlugWC = cateSlugWC.Split(',');
            var listCateNameWC = cateName.Split(',');
            var subCateSlugWC = listCateSlugWC.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
            var subCateNameWC = listCateNameWC.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
            slugWC = subCateSlugWC.Count > 0 ? subCateSlugWC[0] : listCateSlugWC[0];
            cateNameWC = subCateNameWC.Count > 0 ? subCateNameWC[0] : listCateNameWC[0];
            return new CateInfo { Slug = slugWC.Trim(), Name = cateNameWC.Trim() };
        }

        private static CateInfo GetEventEuroInfo(string cateSlugWC)
        {
            var cateEventWC = PostHelper.GetEventCates();
            var listCateSlugWC = cateSlugWC.Split(',');
            foreach (var slug in listCateSlugWC)
            {
                var cate = cateEventWC.Find(item => item.Slug == slug.Trim());
                if (cate != null)
                {
                    return cate;
                }
            }
            return null;
        }
    }
}