namespace SabaNews.Site.Views.Shared.Components.LeftMenuProfile
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class LeftMenuProfileComponent : ViewComponent
    {
        private readonly ISessionService sessionService;
        private readonly IProfileService profileService;

        public LeftMenuProfileComponent(
            ISessionService sessionService,
            IProfileService profileService)
        {
            this.sessionService = sessionService;
            this.profileService = profileService;
        }

        public async Task<IViewComponentResult> InvokeAsync(string page = "")
        {
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            ViewBag.Page = page;
            return View(user);
        }
    }
}
