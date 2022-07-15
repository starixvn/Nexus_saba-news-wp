namespace SabaNews.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;

    public interface IFetchDataService
    {
        Task<PostViewModel> GetHomePageViewModel();

        Task<IEnumerable<Menu>> GetMenuData();

        Task<BlogViewModel> GetCategoryViewModel(GetPostsByCateSlugsParam param);

        Task<Euro2020ViewModel> GetEuro2020ViewModel();
    }
}