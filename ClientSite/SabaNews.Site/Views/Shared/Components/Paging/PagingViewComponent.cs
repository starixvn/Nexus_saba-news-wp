namespace SabaNews.Site.Views.Shared.Components.Paging
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Models;
    using System.Threading.Tasks;

    public class PagingViewComponent : ViewComponent
    {
        public IViewComponentResult Invoke(PagingModel model)
        {
            return View(model);
        }
    }
}
