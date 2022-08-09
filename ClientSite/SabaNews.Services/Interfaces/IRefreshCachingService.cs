namespace SabaNews.Services.Interfaces
{
    using System.Threading.Tasks;

    public interface IRefreshCachingService
    {
        Task UpdateHomePageCaching();

        Task UpdateMenuCaching();

        Task UpdateCategoryCaching();

        Task UpdateSitemapData();

        Task UpdateNewsSitemapData();

        Task UpdateRSSData();

        Task UpdateBannerCaching();

        Task UpdateWorldCupCaching();
    }
}