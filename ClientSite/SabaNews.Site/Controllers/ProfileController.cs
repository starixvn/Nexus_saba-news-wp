namespace SabaNews.Site.Controllers
{
    using BCrypt.Net;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Newtonsoft.Json;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Email;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Models.ViewModel.Profile;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Attributes;
    using System;
    using System.Collections.Generic;
    using System.IO;
    using System.Net.Http.Headers;
    using System.Threading.Tasks;

    [SabaAuth]
    public class ProfileController : Controller
    {
        private readonly IProfileService profileService;
        private readonly ISessionService sessionService;
        private readonly IPostService postService;
        private readonly IAuthService authService;
        private readonly IPathService pathService;
        private readonly IEmailService emailService;

        public ProfileController(
            IProfileService profileService,
            ISessionService sessionService,
            IPostService postService,
            IAuthService authService,
            IPathService pathService,
            IEmailService emailService)
        {
            this.profileService = profileService;
            this.sessionService = sessionService;
            this.postService = postService;
            this.authService = authService;
            this.pathService = pathService;
            this.emailService = emailService;
        }
        [Route("Profile")]
        public async Task<IActionResult> Index()
        {
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            sessionService.User = user;
            var model = new ProfileViewModel
            {
                Title = Constants.Profile + sessionService.User.Name + Constants.ProfileSiteName,
                Page = Constants.ProfileIndexPage,
                ErrorConnectEmail = sessionService.User.ErrorConnectWrongEmail,
                User = user
            };
            return View(model);
        }

        [HttpPost]
        public async Task<IActionResult> SavePost(int postID)
        {
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            List<SavePost> listSavePosts = user.HandleSavePosts;
            var post = listSavePosts.Find(val => val.PostID == postID);
            if (post != null)
            {
                listSavePosts = listSavePosts.FindAll(val => val.PostID != postID);
            }
            else
            {
                var newSavePost = new SavePost
                {
                    PostID = postID,
                    Date = DateTime.Now.ToString("yyyy-MM-dd")
                };
                listSavePosts.Add(newSavePost);
            }
            var listPosts = JsonConvert.SerializeObject(listSavePosts);
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
                    HandleSavePosts = listSavePosts,
                    Password = sessionService.User.Password,
                    Photo =  sessionService.User.Photo
                };
            }
            return Json(commandResult);
        }

        public async Task<ActionResult> ListSavedPosts()
        {
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            var savedPosts = user.HandleSavePosts;
            var listPostID = string.Join(",", savedPosts.ConvertAll(val => val.PostID));
            var posts = await postService.GetSavedPostsByUserID(listPostID);
            var haveSavedToday = savedPosts.Find(val => val.Date == DateTime.Now.ToString("yyyy-MM-dd"));
            var model = new SavedPostViewModel
            {
                Posts = posts,
                ToDayPost = haveSavedToday,
                User = user,
                Title = Constants.Profile + user.Name + Constants.ProfileSiteName,
                Page = Constants.ProfileListPostsPage
            };
            return View(model);
        }

        public async Task<ActionResult> UpdateProfile(string type, string value)
        {
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
                case "subscribe":
                    user.IsSubscribe = int.Parse(value);
                    break;
                default:
                    throw new InvalidOperationException();
            }
            var commandResult =  await profileService.UpdateUserInfoAsync(user);
            if (commandResult.IsSuccessful)
            {
                sessionService.User = user;
            }
            return Json(commandResult);

        }

        public async Task<ActionResult> UpdateProfilePassword(string oldPassword, string newPassword)
        {
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            if (!authService.ComparePassword(oldPassword, user.Password))
            {
                return Json(new { isSuccessful = false, message = Constants.WrongPassword });
            }
            user.Password = BCrypt.HashPassword(newPassword);
            var commandResult = await profileService.UpdateUserInfoAsync(user);
            if (commandResult.IsSuccessful)
            {
                sessionService.User = user;
            }
            return Json(commandResult);

        }

        public async Task<ActionResult> UpdatePhoto(IFormFile file)
        {
           
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            string filename = ContentDispositionHeaderValue.Parse(file.ContentDisposition).FileName.Trim('"');
            filename = pathService.EnsureCorrectFilename(filename);
            filename = DateTime.Now.Millisecond + filename ;
            var filePath = pathService.GetPathAndFilenameUpload(filename);
            using (FileStream output = System.IO.File.Create(pathService.GetPathAndFilenameUpload(filename)))
                await file.CopyToAsync(output);

            if (System.IO.File.Exists(pathService.GetPathAndFilenameUpload(user.Photo)))
            {
                System.IO.File.Delete(pathService.GetPathAndFilenameUpload(user.Photo));
            }
            user.Photo = filename;
            var linkPhoto = "";
            var commandResult = await profileService.UpdateUserInfoAsync(user);
            if (commandResult.IsSuccessful)
            {
                linkPhoto = "uploads/" + filename;
                sessionService.User = user;
            }
            return Json( new { commandResult, linkPhoto, filePath });
        }

        public async Task<ActionResult> SocialDisconnect(int userId, string type)
        {
            var commandResult = await profileService.DisconnectLoginTypeAsync(userId, type);
            if (commandResult.IsSuccessful && string.IsNullOrEmpty(commandResult.Message) && string.IsNullOrEmpty(sessionService.User.Password))
            {
                sessionService.ClearAll();
                await HttpContext.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);
            }
            return Json(commandResult);
        }

        public IActionResult ConfirmUnsubscribe(int userId, string token)
        {
            var model = new UnsubscribeViewModel
            {
                UserId = userId,
                Token = token
            };
            return View(model);
        }

        [HttpPost]
        public async Task<ActionResult> Unsubscribe(int userId, string token)
        {
            var result = await profileService.UnSubcriteUserAsync(userId, token);
            return PartialView("_Unsubscribe", result.IsSuccessful);
        }

        [Route("VerifySendEmailLink")]
        public async Task<ActionResult> VerifySendEmailLink(string returnUrl, string token = "")
        {
            if (token == "")
            {
                return Redirect(returnUrl);
            }
            var command = new VerifyTokenEmailDetailCommand
            {
                TokenId = token
            };
            var result = await emailService.VerifyTokenEmailDetailAsync(command);
            if (!result.IsSuccessful)
            {
                return View(Constants.NotfoundView);
            }
            return Redirect(returnUrl);
        }
    }
}
