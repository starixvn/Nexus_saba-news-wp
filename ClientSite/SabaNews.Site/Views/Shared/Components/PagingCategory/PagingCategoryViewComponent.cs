namespace SabaNews.Site.Views.Shared.Components.PagingCategoryViewComponent
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Models;

    public class PagingCategoryViewComponent : ViewComponent
    {
        public IViewComponentResult Invoke(PagingModel model)
        {
            return View(model);
        }
    }
}
