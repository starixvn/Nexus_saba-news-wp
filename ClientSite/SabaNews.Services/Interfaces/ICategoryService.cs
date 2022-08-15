namespace SabaNews.Services.Interfaces
{
    using SabaNews.Models.Categories;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface ICategoryService
    {
        Task<IList<CategoryGeneral>> GetCateParentsAsync(string slug);
    }
}
