namespace SabaNews.Services.Interfaces
{
    using SabaNews.Models.ViewModel;
    using System.Threading.Tasks;

    public interface IFetchDataService
    {
        Task<PostViewModel> GetHomePageViewModel();
    }
}