namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Constants;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;

    public class FetchDataService : IFetchDataService
    {
        private readonly IPostService postService;

        public FetchDataService(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<PostViewModel> GetHomePageViewModel()
        {
            var newPosts = await postService.GetRecentPostsAsync(Constants.VideoSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var footballPosts = await postService.GetPostsBySlugAsync(Constants.FootballSlug, Constants.FromFirstItem, Constants.GetEightItems);
            var basketballPosts = await postService.GetPostsBySlugAsync(Constants.BasketballSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var tennisPosts = await postService.GetPostsBySlugAsync(Constants.TennisSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var videoPosts = await postService.GetPostsBySlugAsync(Constants.VideoSlug, Constants.FromFirstItem, Constants.GetTenItems);
            var guessPosts = await postService.GetPostsBySlugAsync(Constants.GuessSlug, Constants.FromFirstItem, Constants.GetThreeItems);
            var backstagePosts = await postService.GetPostsBySlugAsync(Constants.BackstageSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var transferPosts = await postService.GetPostsBySlugAsync(Constants.TransferSlug, Constants.FromFirstItem, Constants.GetFiveItems);
            var category = await postService.GetPageDetailBySlugAsync(Constants.HomeSlug);

            var viewModel = new PostViewModel
            {
                NewPosts = (newPosts.Posts).ToArray(),
                FootballPosts = (footballPosts).ToArray(),
                BasketballPosts = (basketballPosts).ToArray(),
                TennisPosts = (tennisPosts).ToArray(),
                VideoPosts = (videoPosts).ToArray(),
                GuessPosts = (guessPosts).ToArray(),
                BackstagePosts = (backstagePosts).ToArray(),
                TransferPosts = (transferPosts).ToArray(),
                Category = category
            };

            return viewModel;
        }

        public async Task<CategoryViewModel> GetCategoryViewModel(string slug, int page)
        {
            const int perPage = Constants.Perpage;
            var controller = "category";
            if (slug == Constants.VideoSlug)
            {
                controller = "video";
            }
            var start = (page - 1) * perPage;
            PostTemplate postTemplate;
            if (slug == Constants.NewPostSlug)
            {
                postTemplate = await postService.GetRecentPostsAsync(Constants.VideoSlug, start, perPage);
            }
            else
            {
                postTemplate = await postService.GetPostsByCateSlugAsync(slug, start, perPage);
            }
            

            if (!postTemplate.Posts.Any())
            {
                return null;
            }
            var cate = await postService.GetCateDetailAsync(slug);
            var totalItems = postTemplate.TotalItems;

            var ViewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = cate.SEOTitle ??= cate.Title,
                Description = cate.Description,
                CurrentPage = page,
                PageSize = perPage,
                TotalPages = (int)Math.Ceiling(totalItems / (double)perPage),
                CategoryId = cate.CatId,
                Controller = controller,
                Slug = slug
            };

            var Model = new CategoryViewModel
            {
                Posts = (postTemplate.Posts).ToArray(),
                Category = cate,
                ViewData = ViewData
            };
            return Model;
        }

        public async Task<IEnumerable<Menu>> GetMenuData()
        {
            var menus = await postService.GetMenuAsync("New Site Menu");
            return menus;
        }
    }
}