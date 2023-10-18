
namespace SabaNews.Site.Views.Shared.Components.Header
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using System.Threading.Tasks;

    public class HeaderViewComponent : ViewComponent
    {
        private readonly ISessionService sessionService;

        public HeaderViewComponent(ISessionService sessionService)
        {
            this.sessionService = sessionService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var isAuthenticated = sessionService.User != null;
            var model = new TopHeaderViewModel
            {
                IsAuthenticated = isAuthenticated,
                IsAdmin = isAuthenticated && sessionService.User.Metadata.Contains(Role.Administrator)
            };
            if (isAuthenticated)
            {
                model.User = sessionService.User;
            }
            return View(model);
        }
    }
}
