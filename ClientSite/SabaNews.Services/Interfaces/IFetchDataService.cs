namespace SabaNews.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Models;
    using SabaNews.Models.Home;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Event.WorldCup;
    using SabaNews.Models.ViewModel.Posts;

    public interface IFetchDataService
    {
        Task<PostViewModel> GetHomePageViewModel();

        Task<IEnumerable<Menu>> GetMenuData();

        Task<BlogViewModel> GetCategoryViewModel(GetPostsByCateSlugsParam param);

        Task<Euro2020ViewModel> GetEuro2020ViewModel();

        Task<WorldCupViewModel> GetWorldCupViewModel();

        Task<(League, IList<Match>)> GetWCFixtureData();

        Task<List<MatchLeagueModel>> GetHomepageFixture();
        Task<List<StandingLeagueModel>> GetHomepageStanding();
    }
}