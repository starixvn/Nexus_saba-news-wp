namespace SabaNews.Site.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class ProfileController : Controller
    {
        private readonly IProfileService profileService;
        private readonly ISessionService sessionService;

        public ProfileController(
            IProfileService profileService,
            ISessionService sessionService)
        {
            this.profileService = profileService;
            this.sessionService = sessionService;
        }
        [Route("Profile")]
        public async Task<IActionResult> Index()
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return View("EmptyProfile");
            }

            return View();
        }

        public async Task<IActionResult> SavePost(int postID)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Json(new { isSuccessful = false });
            }
            List<int> newListSavePosts = sessionService.User.HandleSavePosts;
            if (newListSavePosts.Contains(postID))
            {
                newListSavePosts = newListSavePosts.FindAll(val => val != postID);
            }
            else
            {
                newListSavePosts.Add(postID);
            }
            var listPosts = JsonConvert.SerializeObject(newListSavePosts);

            var commandResult = await profileService.UpdateUserSavePostAsync(sessionService.User.Id, listPosts);
            if (commandResult.IsSuccessful)
            {
                sessionService.User = new UserSession
                {
                    Id = sessionService.User.Id,
                    Email = sessionService.User.Email,
                    Name = sessionService.User.Name,
                    EmailConfirmed = sessionService.User.EmailConfirmed,
                    LoginType = sessionService.User.LoginType,
                    SavedPosts = listPosts,
                    HandleSavePosts = newListSavePosts
                };
            }
            return Json(commandResult);
        }
    }
}
