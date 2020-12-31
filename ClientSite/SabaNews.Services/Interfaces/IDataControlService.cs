namespace SabaNews.Services.Interfaces
{
    using sabanews.Models;
    using System.Threading.Tasks;

    public interface IDataControlService
    {
        Task<PostViewModel> GetHomePageViewModel();
    }
}