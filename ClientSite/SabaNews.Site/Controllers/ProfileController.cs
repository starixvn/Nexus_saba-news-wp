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
    using SabaNews.Models.ViewModels;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.IO;
    using System.Net.Http.Headers;
    using System.Threading.Tasks;

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
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Redirect(Url.Action("Index", "Home"));
            }
            ViewBag.Title = Constants.Profile + sessionService.User.Name + Constants.ProfileSiteName;
            ViewBag.Page = Constants.ProfileIndexPage;
            ViewBag.ErrorConnectEmail = sessionService.User.ErrorConnectWrongEmail;
            var User = await profileService.GetUserByIdAsync(sessionService.User.Id);
            sessionService.User = User;

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
                    HandleSavePosts = newListSavePosts,
                    Password = sessionService.User.Password,
                    Photo =  sessionService.User.Photo
                };
            }
            return Json(commandResult);
        }

        public async Task<ActionResult> ListSavedPosts()
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Redirect(Url.Action("Index", "Home"));
            }
            
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            var savedPosts = user.HandleSavePosts;
            var listPostID = string.Join(",", savedPosts.ConvertAll(val => val.PostID));
            var posts = await postService.GetSavedPostsByUserID(listPostID);
            var haveSavedToday = savedPosts.Find(val => val.Date == DateTime.Now.ToString("yyyy-MM-dd"));
            var Model = new SavedPostViewModel
            {
                Posts = posts,
                ToDayPost = haveSavedToday,
                User = user
            };
            ViewBag.Title = Constants.Profile + user.Name + Constants.ProfileSiteName;
            ViewBag.Page = Constants.ProfileListPostsPage ;

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
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Json(new { isSuccessful = false });
            }
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
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Json(new { isSuccessful = false });
            }

            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            string filename = ContentDispositionHeaderValue.Parse(file.ContentDisposition).FileName.Trim('"');
            filename = EnsureCorrectFilename(filename);
            filename = DateTime.Now.Millisecond + filename ;
            var filePath = GetPathAndFilename(filename);
            using (FileStream output = System.IO.File.Create(GetPathAndFilename(filename)))
                await file.CopyToAsync(output);

            if (System.IO.File.Exists(GetPathAndFilename(user.Photo)))
            {
                System.IO.File.Delete(GetPathAndFilename(user.Photo));
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

        public async Task<ActionResult> Unsubscribe(int userId, string token)
        {
            var result = await profileService.UnSubcriteUserAsync(userId, token);
            return View(result.IsSuccessful);
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


        private static string EnsureCorrectFilename(string filename)
        {
            if (filename.Contains("\\"))
            {
                filename = filename[(filename.LastIndexOf("\\", StringComparison.OrdinalIgnoreCase) + 1)..];
            }


            return filename;
        }

        private string GetPathAndFilename(string filename) => pathService.MapAbsoluteRootPath("//upload//" + filename);
    }
}
