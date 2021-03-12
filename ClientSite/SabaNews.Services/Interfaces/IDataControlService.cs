namespace SabaNews.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AspNetCore.SEOHelper.Sitemap;
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;

    public interface IDataControlService
    {
        Task<PostViewModel> GetHomePageViewModel();

        Task<IEnumerable<Menu>> GetMenuData();

        Task<CategoryViewModel> GetCategoryViewModel(string slug, int page);

        Task<IEnumerable<SitemapNode>> GenerateSitmapXmlData();
    }
}