namespace SabaNews.Services
{
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;

    public class FetchDataService : IFetchDataService
    {
        private readonly IPostService postService;
        private readonly IConfiguration configuration;

        public FetchDataService(IPostService postService, IConfiguration configuration)
        {
            this.postService = postService;
            this.configuration = configuration;
        }

        public async Task<PostViewModel> GetHomePageViewModel()
        {
            var paramGetRecent = new GetRecentPostsParam
            {
                Start = Constants.FromFirstItem,
                End = Constants.GetFourItems
            };
            var newPosts = await postService.GetRecentPostsAsync(paramGetRecent);
            var footballPosts = await postService.GetPostsBySlugAsync(Constants.FootballSlug, Constants.FromFirstItem, Constants.GetEightItems);
            var otherPosts = await postService.GetPostsBySlugAsync(Constants.OtherPostSlug, Constants.FromFirstItem, Constants.GetFiveItems);
            var tennisPosts = await postService.GetPostsBySlugAsync(Constants.TennisSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var videoPosts = await postService.GetPostsBySlugAsync(Constants.VideoSlug, Constants.FromFirstItem, Constants.GetTenItems);
            var guessPosts = await postService.GetPostsBySlugAsync(Constants.GuessSlug, Constants.FromFirstItem, Constants.GetThreeItems);
            var backstagePosts = await postService.GetPostsBySlugAsync(Constants.BackstageSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var transferPosts = await postService.GetPostsBySlugAsync(Constants.TransferSlug, Constants.FromFirstItem, Constants.GetFiveItems);
            var category = await postService.GetPageDetailBySlugAsync(Constants.HomeSlug);

            var viewModel = new PostViewModel
            {
                NewPosts = (newPosts).ToArray(),
                FootballPosts = (footballPosts).ToArray(),
                OtherPosts = (otherPosts).ToArray(),
                TennisPosts = (tennisPosts).ToArray(),
                VideoPosts = (videoPosts).ToArray(),
                GuessPosts = (guessPosts).ToArray(),
                BackstagePosts = (backstagePosts).ToArray(),
                TransferPosts = (transferPosts).ToArray(),
                Category = category
            };

            return viewModel;
        }

        public async Task<BlogViewModel> GetCategoryViewModel(GetPostsByCateSlugsParam param)
        {
            var pageSize = Constants.Perpage;
            var controller = "category";
            var slug = param.Slug1;
            var page = param.Page;
            if (slug == Constants.VideoSlug)
            {
                controller = "video";
            }
            var start = (page - 1) * pageSize;
            IEnumerable<Post> posts;
            int totalItems;
            if (slug == null)
            {
                var paramGetRecent = new GetRecentPostsParam
                {
                    Start = start,
                    End = pageSize
                };
                posts = await postService.GetRecentPostsAsync(paramGetRecent);
                totalItems = paramGetRecent.TotalIItems;
                slug = Constants.NewPostSlug;
            }
            else
            {
                var criteriaCategory = new GetPostsByCateSlugsCriteria
                {
                    Slug1 = param.Slug1,
                    Slug2 = param.Slug2,
                    Slug3 = param.Slug3,
                    Start = start,
                    End = pageSize
                };
                posts = await postService.GetPostsByCateSlugs(criteriaCategory);
                totalItems = criteriaCategory.TotalItems;
                slug = criteriaCategory.Slug;
            }
            if (!posts.Any())
            {
                return null;
            }
            var cate = await postService.GetCateDetailAsync(slug);
            var title = cate.SEOTitle ??= cate.Title;
            pageSize = slug == Constants.VideoSlug ? Constants.PageSizeVideo : Constants.Perpage;
            var header = new HeaderViewModel
            {
                HeadTitle = cate.Title,
                Title = title,
                Description = cate.Description,
                CategoryId = cate.CateId,
                Controller = controller,
                Slug = slug
            };
            var paging = new PagingModel
            {
                CurrentPage = page,
                PageSize = pageSize,
                TotalPages = (int)Math.Ceiling(totalItems / (double)pageSize)
            };

            var slugs = new Slugs
            {
                Slug1 = param.Slug1,
                Slug2 = param.Slug2,
                Slug3 = param.Slug3
            };

            var model = new BlogViewModel
            {
                Posts = posts.ToList(),
                Category = cate,
                Header = header,
                Paging = paging,
                Slugs = slugs
            };
            return model;
        }

        public async Task<IEnumerable<Menu>> GetMenuData()
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);
            return menus;
        }

        public async Task<Euro2020ViewModel> GetEuro2020ViewModel()
        {
            var recentPosts = await postService.GetPostsBySlugAsync(Constants.Euro2020HotNewsSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var totalRanking = await postService.GetPostDetailBySlugAsync(Constants.Euro2020HomeRankingSlug, Constants.postType);
            var identify = await postService.GetPostsBySlugAsync(Constants.Euro2020IdentifySlug, Constants.FromFirstItem, Constants.GetThreeItems);
            var ranking = await postService.GetPostsBySlugAsync(Constants.Euro2020RankingSlug, Constants.FromFirstItem, Constants.Total);
            var videos = await postService.GetPostsBySlugAsync(Constants.Euro2020VideoSlug, Constants.FromFirstItem, Constants.GetTenItems);
            var cate = await postService.GetCateDetailAsync(Constants.Euro2020Slug);
            if (ranking.Any())
            {
                ranking = ranking.Where(item => item.PostExcerpt.Trim().ToLower(CultureInfo.InvariantCulture) != Constants.TemplatePost).OrderBy(item => item.PostDate).ToList();
            }
            var title = cate.SEOTitle ??= cate.Title;
            var viewData = new ViewData
            {
                HeadTitle = cate.Title,
                Title = title,
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

        public async Task<WorldCupViewModel> GetWorldCupViewModel()
        {
            var lastestNews = await postService.GetPostsBySlugAsync(WorldCup2022.HomepageSlug, Constants.FromFirstItem, Constants.GetFiveItems);
            var commentary = await postService.GetPostsBySlugAsync(WorldCup2022.CommentarySlug, Constants.FromFirstItem, Constants.GetFiveItems);
            var videos = await postService.GetPostsBySlugAsync(WorldCup2022.VideoSlug, Constants.FromFirstItem, Constants.GetTenItems);
            var storyPost = await postService.GetPostsBySlugAsync(WorldCup2022.StoryPostSlug, Constants.FromFirstItem, Constants.GetFourItems, Constants.storyPostType);
            var cate = await postService.GetCateDetailAsync(WorldCup2022.HomepageSlug);
            
            var Model = new WorldCupViewModel
            {
                LastestNews = lastestNews,
                Commentary = commentary,
                Videos = videos,
                StoryPost = storyPost,
                Category = cate,
            };
            return Model;
        }
    }
}