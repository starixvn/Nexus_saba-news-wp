namespace SabaNews.Services
{
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Category;
    using SabaNews.Models.Categories;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class CategoryService : ICategoryService
    {
        private readonly IBaseService baseService;

        public CategoryService(IBaseService baseService)
        {
            this.baseService = baseService;
        }
    }
}
