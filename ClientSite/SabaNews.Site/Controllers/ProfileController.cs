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
    using BCrypt.Net;
    using Microsoft.AspNetCore.Http;
    using System.Net.Http.Headers;
    using System.IO;
    using SabaNews.Core.Constants;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using SabaNews.Models.Email;
    using System.Linq;
    using SabaNews.Criteria.Email;

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
                linkPhoto = "upload/" + filename;
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

        public async Task<ActionResult> SendMail()
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Redirect(Url.Action("Index", "Home"));
            }
            ViewBag.Title = Constants.Profile + sessionService.User.Name + Constants.ProfileSiteName;
            ViewBag.Page = Constants.ProfileSendMailPage;
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            var emails = await emailService.GetEmailByUser(new GetEmailByUserCriteria{ UserId= user.Id });
            var Model = new EmailViewModel
            {
                User = user,
                Emails = emails
            };
            return View(Model);
        }

        [HttpPost]
        public async Task<ActionResult> SaveDataAndSendEmail(EmailModel model)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                var error = new { isSuccessful= false, message= "Please login first" };
                return Json(new { result = error });
            }
            var users = await profileService.GetAllUserAsync();
            var userNumber = users.Count();
            model.CreatedBy = sessionService.User.Id;
            model.SendDate = DateTime.UtcNow;
            var result = await emailService.CreateEmailAsync(model);
            var returnUrl = "/" + model.EmailReturnLink;
            model.EmailReturnLink = returnUrl.Replace("//", "/");

            if (result.IsSuccessful)
            {
                foreach (var user in users)
                {
                    var token = Guid.NewGuid().ToString();
                    var returnLink = Url.Action("ClickedSendMailLink", "Profile", new { token, returnUrl = model.EmailReturnLink }, Request.Scheme);
                    var messageReturnLink = string.Format("<div class=\"return_link\">Click vào <a href=\"{0}\">đây</a> để xem chi tiết  <br />Cảm ơn.<br /> Saba Sports </div>", returnLink);
                    var emailContent = model.EmailContent + "<br />" + messageReturnLink;
                    
                    var resultSendMail = await authService.SendMailCkeditorAsync(model.EmailTitle, emailContent, user.Email);
                    var detailModel = new EmailDetailModel
                    {
                        EmailId = model.EmailId,
                        UserId = sessionService.User.Id,
                        EmailAddress = user.Email,
                        EmailContent = emailContent,
                        EmailTitle = model.EmailTitle,
                        TokenId = token,
                        SendTime = DateTime.UtcNow,
                        Status = resultSendMail.Code
                    };
                    detailModel.Status = resultSendMail.Code;
                    await emailService.CreateEmailDetailAsync(detailModel);
                }
            }
            return Json(new { result, userNumber });
        }

        public async Task<ActionResult> ClickedSendMailLink(string token, string returnUrl)
        {
            var command = new VerifyTokenEmailDetailCommand
            {
                TokenId = token
            };
            var result = await emailService.VerifyTokenEmailDetailAsync(command);
            if (!result.IsSuccessful)
            {
                return View("~/Views/Home/EmptyBlog.cshtml");
            }
            return Redirect(returnUrl);
        }

        public async Task<ActionResult> UploadImageSendMail(IFormFile file)
        {
            string filename = ContentDispositionHeaderValue.Parse(file.ContentDisposition).FileName.Trim('"');
            filename = EnsureCorrectFilename(filename);
            filename = DateTime.Now.Millisecond + filename;
            string filePath = "";
            string message = "";
            try
            {
                using (FileStream output = System.IO.File.Create(GetPathAndFilename(filename)))
                {
                    await file.CopyToAsync(output);
                    filePath = output.Name;
                }
            }
            catch (Exception ex)
            {
                message = ex.ToString();
            }
            string linkPhoto = "upload/" + filename;
            string baseUrl = $"{Request.Scheme}://{Request.Host}{Request.PathBase}/";
            string url = string.Concat(baseUrl, linkPhoto);

            return Json(new { url, filePath, message });
        }

        public async Task<ActionResult> SendMailDetail(int emailId)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Redirect(Url.Action("Index", "Home"));
            }

            var listEmailDetails = await emailService.GetEmailDetailByEmailId(new GetEmailDetailByEmailIdCriteria { EmailID = emailId });
            ViewBag.Page = Constants.ProfileSendMailPage;
            ViewBag.Title = Constants.Profile + sessionService.User.Name + Constants.ProfileSiteName;
            ViewBag.EmailTitle = listEmailDetails.Any() ? listEmailDetails.FirstOrDefault().EmailTitle : "";
            ViewBag.EmailContent = listEmailDetails.Any() ? listEmailDetails.FirstOrDefault().EmailContent : "";
            return View(listEmailDetails);
        }


        private static string EnsureCorrectFilename(string filename)
        {
            if (filename.Contains("\\"))
            {
                filename = filename.Substring(filename.LastIndexOf("\\", StringComparison.OrdinalIgnoreCase) + 1);
            }


            return filename;
        }

        private string GetPathAndFilename(string filename) => pathService.MapAbsolutePath("wwwroot/upload/" ,  filename);
    }
}
