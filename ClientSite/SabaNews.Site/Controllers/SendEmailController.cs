
namespace SabaNews.Site.Controllers
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Email;
    using SabaNews.Models;
    using SabaNews.Models.Email;
    using SabaNews.Services.Interfaces;
    using System;
    using System.IO;
    using System.Linq;
    using System.Net.Http.Headers;
    using System.Threading.Tasks;
    using System.Web;

    
    public class SendEmailController : Controller
    {
        private readonly IProfileService profileService;
        private readonly ISessionService sessionService;
        private readonly IAuthService authService;
        private readonly IPathService pathService;
        private readonly IEmailService emailService;

        public SendEmailController(
            IProfileService profileService,
            ISessionService sessionService,
            IAuthService authService,
            IPathService pathService,
            IEmailService emailService)
        {
            this.profileService = profileService;
            this.sessionService = sessionService;
            this.authService = authService;
            this.pathService = pathService;
            this.emailService = emailService;
        }

        [Route("SendEmail")]
        public IActionResult Index()
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
                var error = new { isSuccessful = false, message = "Please login first" };
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

        [Route("/SendEmail/List/page/{page}")]
        [Route("/SendEmail/List/")]
        public async Task<ActionResult> List(int page = 1)
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

        public async Task<ActionResult> VerifySendMailLink(string returnUrl, string token = "")
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
            string linkPhoto = "uploads/" + filename;
            string baseUrl = $"{Request.Scheme}://{Request.Host}{Request.PathBase}/";
            string url = string.Concat(baseUrl, linkPhoto);

            return Json(new { url, filePath, error });
        }

        [Route("/SendEmail/Details/{emailId}/page/{page}")]
        [Route("/SendEmail/Details/{emailId}")]
        public async Task<ActionResult> Detail(int emailId, int page = 1)
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

        private string GetPathAndFilename(string filename) => pathService.MapAbsoluteRootPath("//uploads//" + filename);
    }
}
