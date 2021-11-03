namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Models.Banner;
    using System.Threading.Tasks;

    public interface IBannerService
    {
        Task<Banner> GetBannerInfoAsync();

        Task<CommandResult> UpdateBannerInfoAsync(Banner banner);
    }
}
