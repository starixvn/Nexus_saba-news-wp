namespace SabaNews.Services.Interfaces
{
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Posts;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IDataControlService
    {
        Task<PostViewModel> GetHomePageViewModel();

        Task<IEnumerable<Menu>> GetMenuData();

        Task<CategoryViewModel> GetCategoryViewModel(string slug, int page);

        Task<IEnumerable<SitemapModel>> GenerateSitmapXmlData();

        Task<IEnumerable<NewsSitemapModel>> GenerateNewsSitmapXmlData();

        Task<IEnumerable<Post>> GenerateRSSData();

        Task<Euro2020ViewModel> GetEuro2020PageViewModel();
    }
}