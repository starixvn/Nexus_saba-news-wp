namespace SabaNews.Site.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Models.ViewModels;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class ProfileController : Controller
    {
        private readonly IProfileService profileService;
        private readonly ISessionService sessionService;
        private readonly IPostService postService;
        private readonly IAuthService authService;

        public ProfileController(
            IProfileService profileService,
            ISessionService sessionService,
            IPostService postService,
            IAuthService authService)
        {
            this.profileService = profileService;
            this.sessionService = sessionService;
            this.postService = postService;
            this.authService = authService;
        }
        [Route("Profile")]
        public async Task<IActionResult> Index()
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return View("EmptyProfile");
            }
            var User = await profileService.GetUserByIdAsync(sessionService.User.Id);

            return View(User);
        }

        public async Task<IActionResult> SavePost(int postID)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Json(new { isSuccessful = false });
            }
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            List<SavePost> newListSavePosts = user.HandleSavePosts;
            
            var savePost = newListSavePosts.Find(val => val.PostID == postID);
            if (savePost != null)
            {
                newListSavePosts = newListSavePosts.FindAll(val => val.PostID != postID);
            }
            else
            {
                var newSavePost = new SavePost
                {
                    PostID = postID,
                    Date = DateTime.Now.ToString("yyyy-MM-dd")
                };
                newListSavePosts.Add(newSavePost);
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

        public async Task<ActionResult> ListSavedPosts()
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return View("EmptyProfile");
            }
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            var savedPosts = user.HandleSavePosts;
            var listPostID = string.Join(",", savedPosts.ConvertAll(val => val.PostID));
            var posts = await postService.GetSavedPostsByUserID(listPostID);
            var haveSavedToday = savedPosts.Find(val => val.Date == DateTime.Now.ToString("yyyy-MM-dd"));
            var Model = new SavedPostViewModel
            {
                Posts = posts,
                ToDayPost = haveSavedToday
            };

            return View(Model);
        }

        public async Task<ActionResult> UpdateProfile(string type, string value)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Json(new { isSuccessful = false });
            }
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);

            switch (type)
            {
                case "phone":
                    user.Phone = value;
                    break;
                case "name":
                    user.Name = value;
                    break;
                case "gender":
                    user.Gender = value;
                    break;
                default: break;
            };

            var commandResult =  await profileService.UpdateUserInfoAsync(user);
            return Json(commandResult);

        }

        public async Task<ActionResult> UpdateProfilePassword(string oldPassword, string newPassword)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Json(new { isSuccessful = false });
            }
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            if (!authService.ComparePassword(oldPassword, user.Password))
            {
                return Json(new { isSuccessful = false, message = "Mật khẩu cũ không đúng" });
            }
            user.Password = newPassword;
            var commandResult = await profileService.UpdateUserInfoAsync(user);
            return Json(commandResult);

        }
    }
}
