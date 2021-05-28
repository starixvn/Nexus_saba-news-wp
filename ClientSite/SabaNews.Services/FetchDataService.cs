namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Constants;
    using SabaNews.Criteria;
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
            var paramGetRecent = new PostParam
            {
                Slug = Constants.VideoSlug,
                Start = Constants.FromFirstItem,
                End = Constants.GetFourItems
            };
            var newPosts = await postService.GetRecentPostsAsync(paramGetRecent);
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
            var paramGetRecent = new PostParam
            {
                Slug = Constants.VideoSlug,
                Start = start,
                End = perPage
            };
            var paramGetCategory = new PostParam
            { 
                Slug = slug,
                Start = start,
                End = perPage
            };
            paramGetCategory.Slug = slug;
            var postTemplate = slug == Constants.NewPostSlug ? 
                await postService.GetRecentPostsAsync(paramGetRecent) : 
                await postService.GetPostsByCateSlugAsync(paramGetCategory);
            

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

        public async Task<Euro2020ViewModel> GetEuro2020ViewModel()
        {
            var recentPosts = await postService.GetPostsBySlugAsync(Constants.Euro2020HotNewsSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var totalRanking = await postService.GetPostDetailBySlugAsync(Constants.Euro2020HomeRankingSlug, Constants.postType);
            var identify = await postService.GetPostsBySlugAsync(Constants.Euro2020IdentifySlug, Constants.FromFirstItem, Constants.GetFirstItem);
            var ranking = await postService.GetPostsBySlugAsync(Constants.Euro2020RankingSlug, Constants.FromFirstItem, Constants.GetEightItems);
            var videos = await postService.GetPostsBySlugAsync(Constants.Euro2020VideoSlug, Constants.FromFirstItem, Constants.GetTenItems);
            var cate = await postService.GetCateDetailAsync(Constants.Euro2020Slug);
            if (ranking.Any())
            {
                ranking = ranking.Where(item => item.PostSeoTitle.Trim().ToLower(CultureInfo.InvariantCulture) != Constants.TemplatePost).OrderBy(item => item.PostDate).ToList();
            }

            var viewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = cate.SEOTitle ??= cate.Title,
                Description = cate.Description
            };
            var Model = new Euro2020ViewModel
            {
                TotalRanking = totalRanking,
                Ranking = ranking.ToArray(),
                HotNews = recentPosts.ToArray(),
                Videos = videos.ToArray(),
                Identify = identify.ToArray(),
                Category = cate,
                ViewData = viewData
            };
            return Model;
        }
    }
}