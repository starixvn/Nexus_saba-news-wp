namespace SabaNews.Services.Interfaces
{
    using SabaNews.Models;
    using SabaNews.Models.ViewModel;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IDataControlService
    {
        Task<PostViewModel> GetHomePageViewModel();

        Task<IEnumerable<Menu>> GetMenuData();
    }
}