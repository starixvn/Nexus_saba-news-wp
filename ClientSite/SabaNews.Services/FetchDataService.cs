namespace SabaNews.Services
{
    using SabaNews.Core.Constants;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;
    using System.Linq;
    using System.Threading.Tasks;

    public class FetchDataService : IFetchDataService
    {
        private readonly IPostService postService;

        public FetchDataService(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<PostViewModel> GetHomePageViewModel()
        {
            var newPosts = postService.GetPostsBySlugAsync(Constants.NewPostSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var footballPosts = postService.GetPostsBySlugAsync(Constants.FootballSlug, Constants.FromFirstItem, Constants.GetEightItems);
            var basketballPosts = postService.GetPostsBySlugAsync(Constants.BasketballSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var tennisPosts = postService.GetPostsBySlugAsync(Constants.TennisSlug, Constants.FromFirstItem, Constants.GetFourItems);
            var videoPosts = postService.GetPostsBySlugAsync(Constants.VideoSlug, Constants.FromFirstItem, Constants.GetSixItems);
            var guessPosts = postService.GetPostsBySlugAsync(Constants.GuessSlug, Constants.FromFirstItem, Constants.GetThreeItems);
            var backstagePosts = postService.GetPostsBySlugAsync(Constants.BackstageSlug, Constants.FromFirstItem, Constants.GetThreeItems);
            var transferPosts = postService.GetPostsBySlugAsync(Constants.TransferSlug, Constants.FromFirstItem, Constants.GetFiveItems);
            var category = postService.GetPageDetailBySlugAsync(Constants.HomeSlug);

            await Task.WhenAll(newPosts, footballPosts, basketballPosts, tennisPosts, videoPosts, guessPosts, backstagePosts, transferPosts, category);
            var viewModel = new PostViewModel
            {
                NewPosts = (await newPosts).ToArray(),
                FootballPosts = (await footballPosts).ToArray(),
                BasketballPosts = (await basketballPosts).ToArray(),
                TennisPosts = (await tennisPosts).ToArray(),
                VideoPosts = (await videoPosts).ToArray(),
                GuessPosts = (await guessPosts).ToArray(),
                BackstagePosts = (await backstagePosts).ToArray(),
                TransferPosts = (await transferPosts).ToArray(),
                Category = await category
            };

            return viewModel;
        }
    }
}