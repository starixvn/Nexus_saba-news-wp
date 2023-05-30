namespace SabaNews.Services
{
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;

    public class CategoryService : ICategoryService
    {
        private readonly IBaseService baseService;

        public CategoryService(IBaseService baseService)
        {
            this.baseService = baseService;
        }
    }
}
