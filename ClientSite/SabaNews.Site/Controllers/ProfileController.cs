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
    using SabaNews.Models;
    using System.Web;

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
            string baseUrl = string.Concat($"{Request.Scheme}://{Request.Host}{Request.PathBase}", "/Profile/VerifySendMailLink?{token}");
            ViewBag.BaseUrl = baseUrl;
            return View();
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
            var subScribedUsers = users.Where(item => item.IsSubcribe == 1).ToList();
            var userNumber = subScribedUsers.Count();
            model.CreatedBy = sessionService.User.Id;
            model.SendDate = DateTime.UtcNow;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);


            var result = await emailService.CreateEmailAsync(model);
            

            if (result.IsSuccessful)
            {
                foreach (var user in subScribedUsers)
                {
                    var token = Guid.NewGuid().ToString();
                    var tokenParam = "token=" + token;
                    var UnsubcribeLink = Url.Action("Unsubscribe", "Profile", new { UserId = user.Id }, Request.Scheme);
                    var messageReturnLink = string.Format("<div class=\"return_link\">Click vào <a href=\"{0}\">đây</a> nếu sau này bạn không muốn nhận email từ chúng tôi  </div>", UnsubcribeLink);
                    var emailContent = model.EmailContent.Replace("{token}", tokenParam) + messageReturnLink;
                    
                    var resultSendMail = await authService.SendMailEventAsync(model.EmailTitle, model.EmailContent, user.Email);
                    var detailModel = new EmailDetailModel
                    {
                        EmailId = model.EmailId,
                        UserId = user.Id,
                        EmailAddress = user.Email,
                        EmailContent = emailContent,
                        EmailTitle = model.EmailTitle,
                        TokenId = token,
                        SendTime = DateTime.UtcNow,
                        Status = resultSendMail.Code
                    };
                    await emailService.CreateEmailDetailAsync(detailModel);
                }
            }
            return Json(new { result, userNumber });
        }

        [Route("/Profile/ListSendMail/page/{page}")]
        [Route("/Profile/ListSendMail/")]
        public async Task<ActionResult> ListSendMail(int page = 1)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Redirect(Url.Action("Index", "Home"));
            }
            const int perPage = Constants.Perpage;
            var start = (page - 1) * perPage;
            var user = await profileService.GetUserByIdAsync(sessionService.User.Id);
            var criteria = new GetEmailByUserCriteria { UserId = user.Id, Start = start, End = Constants.Perpage };
            var emails = await emailService.GetEmailByUserAsync(criteria);
            var ViewData = new ViewData
            {
                CurrentPage = page,
                PageSize = perPage,
                TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)perPage),
            };
            var Model = new EmailViewModel
            {
                Emails = emails,
                ViewData = ViewData
            };
            ViewBag.Page = Constants.ProfileListSendMailPage;
            return View(Model);
        }

        public async Task<ActionResult> VerifySendMailLink(string returnUrl, string token = "" )
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
            string error = "";
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
                error = ex.ToString();
            }
            string linkPhoto = "upload/" + filename;
            string baseUrl = $"{Request.Scheme}://{Request.Host}{Request.PathBase}/";
            string url = string.Concat(baseUrl, linkPhoto);

            return Json(new { url, filePath, error });
        }

        [Route("/Profile/SendMailDetail/{emailId}/page/{page}")]
        [Route("/Profile/SendMailDetail/{emailId}")]
        public async Task<ActionResult> SendMailDetail(int emailId, int page = 1)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Redirect(Url.Action("Index", "Home"));
            }
            const int perPage = Constants.Perpage;
            var start = (page - 1) * perPage;
            var criteria = new GetEmailDetailByEmailIdCriteria
            {
                EmailID = emailId,
                Start = start,
                End = perPage
            };
            var listEmailDetails = await emailService.GetEmailDetailByEmailIdAsync(criteria);
            var email = await emailService.GetEmailByIdAsync(new GetEmailByIdCriteria { Id = emailId });
            var ViewData = new ViewData
            {
                CurrentPage = page,
                PageSize = perPage,
                TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)perPage),
            };
            var Model = new EmailDetailViewModel
            {
                ListEmailDetails = listEmailDetails,
                ViewData = ViewData
            };

            ViewBag.Page = Constants.ProfileSendMailPage;
            ViewBag.Title = Constants.Profile + sessionService.User.Name + Constants.ProfileSiteName;
            ViewBag.EmailTitle = email.EmailTitle;
            ViewBag.EmailContent = email.EmailContent;
            return View(Model);
        }

        public async Task<ActionResult> ResendEmail(int userId, int emailId)
        {
            var detail = await emailService.GetDetailByEmailIdUserIdAsync(new GetDetailEmailDetailCriteria { UserId = userId, EmailId = emailId });
            var resultSendMail = await authService.SendMailEventAsync(detail.EmailTitle, detail.EmailContent, detail.EmailAddress);
            if (resultSendMail.Code == 0)
            {
                var command = await emailService.UpdateEmailDetailStatusAsync(new UpdateEmailDetailStatusCommand { Status = resultSendMail.Code, EmailId = emailId, UserId = userId });
                return Json(command);
            }
            return Json(new { isSuccessful = false });
        }

        public async Task<ActionResult> Unsubscribe(int UserId)
        {
            var command = await profileService.UnSubcriteUserAsync(UserId);
            return View();
        }


        private static string EnsureCorrectFilename(string filename)
        {
            if (filename.Contains("\\"))
            {
                filename = filename.Substring(filename.LastIndexOf("\\", StringComparison.OrdinalIgnoreCase) + 1);
            }


            return filename;
        }

        private string GetPathAndFilename(string filename) => pathService.MapAbsoluteRootPath("//upload//" + filename);
    }
}
