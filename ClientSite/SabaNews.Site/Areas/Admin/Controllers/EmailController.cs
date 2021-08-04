namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Auth;
    using SabaNews.Criteria.Email;
    using SabaNews.Models.Email;
    using SabaNews.Services.Interfaces;
    using System;
    using System.IO;
    using System.Linq;
    using System.Net.Http.Headers;
    using System.Threading.Tasks;
    using System.Web;

    public class EmailController : AdminController
    {
        private readonly IProfileService profileService;
        private readonly ISessionService sessionService;
        private readonly IPathService pathService;
        private readonly IEmailService emailService;
        private readonly IConfiguration configuration;
        private const string SendMailTemplates = "EmailTemplates//SendMailEvent.txt";

        public EmailController(
            IProfileService profileService,
            ISessionService sessionService,
            IPathService pathService,
            IEmailService emailService,
            IConfiguration configuration)
        {
            this.profileService = profileService;
            this.sessionService = sessionService;
            this.pathService = pathService;
            this.emailService = emailService;
            this.configuration = configuration;
        }

        public async Task<ActionResult> Index()
        {
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria());
            return View(users);
        }

        public async Task<ActionResult> GetListEmailData(EmailFilterViewModel param)
        {
            const int perPage = Constants.Perpage;
            var start = (param.Page - 1) * perPage;
            var criteria = new GetEmailsCriteria 
            {
                Search = param.Search ??= "", 
                DateFrom = param.DateFrom,
                DateTo = param.DateTo, 
                End = perPage,
                Start = start 
            };
            var emails = await emailService.GetEmailsAsync(criteria);
            var paging = new PagingModel
            {
                CurrentPage = param.Page,
                PageSize = perPage,
                TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)perPage),
                SearchValue = param.Search,
                DateFrom = param.DateFrom,
                DateTo = param.DateTo,
            };
            var model = new EmailViewModel
            {
                Emails = emails,
                Paging = paging
            };
            return PartialView("_ListEmailData", model);
        }

        public IActionResult Create()
        {
            var rawUrl = string.Concat($"{configuration.GetValue<string>("BaseUrl")}", "VerifySendEmailLink?{token}");
            ViewBag.rawUrl = rawUrl;
            return View();
        }

        [HttpPost]
        public async Task<ActionResult> SaveDataAndSendEmail(EmailModel model)
        {
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria { UserRole = Constants.CustomerRole });
            var subscribedUsers = users.Where(item => item.IsSubcribe == 1).ToList();
            var userNumber = subscribedUsers.Count;
            model.CreatedBy = sessionService.User.Id;
            model.SendDate = DateTime.Now;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
            model.Status = Constants.EmailSent;
            var result = await emailService.CreateEmailAsync(model);

            if (result.IsSuccessful)
            {
                foreach (var user in subscribedUsers)
                {
                    var token = Guid.NewGuid().ToString();
                    var unsubcribeLink = Url.Action("Unsubscribe", "Profile", new { UserId = user.Id, Area = "" }, Request.Scheme);
                    var messageUnsubcribe = string.Format(Constants.UnsubscribeTemplate, unsubcribeLink);
                    var emailContent = model.EmailContent.Replace("{token}", string.Concat("token=" + token)) + messageUnsubcribe;
                    var resultSendMail = await emailService.SendMailWithTemplateAsync(model.EmailTitle, emailContent, user.Email, SendMailTemplates);
                    var emailData = new EmailDetailModel
                    {
                        EmailId = model.Id,
                        UserId = user.Id,
                        EmailAddress = user.Email,
                        EmailContent = emailContent,
                        EmailTitle = model.EmailTitle,
                        TokenId = token,
                        SendTime = DateTime.Now,
                        Status = resultSendMail.Code
                    };
                    await emailService.CreateEmailDetailAsync(emailData);
                }
            }
            return Json(new { result, userNumber });
        }

        [HttpPost]
        public async Task<ActionResult> SaveAsDraft(EmailModel model)
        {
            model.CreatedBy = sessionService.User.Id;
            model.Status = Constants.EmailReview;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
            var result = await emailService.CreateEmailAsync(model);
            return Json(new { result });
        }

        public async Task<ActionResult> ReviewSendEmail()
        {
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria());
            var rawUrl = string.Concat($"{configuration.GetValue<string>("BaseUrl")}", "VerifySendEmailLink?{token}");
            ViewBag.rawUrl = rawUrl;
            return View(users);
        }

        [HttpPost]
        public async Task<ActionResult> SaveAndReviewSendEmail(EmailReviewModel model)
        {
            model.CreatedBy = sessionService.User.Id;
            model.Status = Constants.EmailReview;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
            var result = await emailService.CreateEmailAsync(model);
            var resultSendMail = await emailService.SendMailWithTemplateAsync(model.EmailTitle, model.EmailContent, model.Email, SendMailTemplates);
            return Json(new { result, resultSendMail });
        }

        [HttpPost]
        public async Task<ActionResult> ReviewSendEmail(EmailReviewModel model)
        {
            var email = await emailService.GetEmailByIdAsync(new GetEmailByIdCriteria { Id = model.EmailId });
            var resultSendMail = await emailService.SendMailWithTemplateAsync(email.EmailTitle, email.EmailContent, model.Email, SendMailTemplates);
            return Json(resultSendMail);
        }

        [HttpPost]
        public async Task<ActionResult> SendEmail(int emailId)
        {
            var email = await emailService.GetEmailByIdAsync(new GetEmailByIdCriteria { Id = emailId });
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria { UserRole = Constants.CustomerRole });
            var subscribedUsers = users.Where(item => item.IsSubcribe == 1).ToList();
            var userNumber = subscribedUsers.Count;
            foreach (var user in subscribedUsers)
            {
                var token = Guid.NewGuid().ToString();
                var unsubcribeLink = Url.Action("Unsubscribe", "Profile", new { UserId = user.Id, Area = "" }, Request.Scheme);
                var messageUnsubcribe = string.Format(Constants.UnsubscribeTemplate, unsubcribeLink);
                var emailContent = email.EmailContent.Replace("{token}", string.Concat("token=" + token)) + messageUnsubcribe;
                var resultSendMail = await emailService.SendMailWithTemplateAsync(email.EmailTitle, emailContent, user.Email, SendMailTemplates);
                var emailData = new EmailDetailModel
                {
                    EmailId = email.Id,
                    UserId = user.Id,
                    EmailAddress = user.Email,
                    EmailContent = emailContent,
                    EmailTitle = email.EmailTitle,
                    TokenId = token,
                    SendTime = DateTime.Now,
                    Status = resultSendMail.Code
                };
                await emailService.CreateEmailDetailAsync(emailData);
            }
            var result = await emailService.UpdateStatusAsync(new UpdateStatusCommand { EmailId = emailId, Status = Constants.EmailSent });
            return Json(new { result, userNumber });
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
                using FileStream output = System.IO.File.Create(GetPathAndFilename(filename));
                await file.CopyToAsync(output);
                filePath = output.Name;
            }
            catch (Exception ex)
            {
                error = ex.ToString();
            }
            string linkPhoto = "uploads/" + filename;
            string baseUrl = configuration.GetValue<string>("BaseUrl");
            string url = string.Concat(baseUrl, linkPhoto);
            return Json(new { url, filePath, error });
        }


        public async Task<ActionResult> Detail(int emailId)
        {
            var email = await emailService.GetEmailByIdAsync(new GetEmailByIdCriteria { Id = emailId });
            var Model = new EmailDetailViewModel
            {
                Email = email
            };
            return View(Model);
        }

        public async Task<ActionResult> GetListEmailDetailData(EmailDetailFilterViewModel param)
        {
            const int perPage = Constants.Perpage;
            var start = (param.Page - 1) * perPage;
            var criteria = new GetEmailDetailByEmailIdCriteria
            {
                EmailID = param.EmailId,
                Search = param.Search ??= "",
                IsClicked = param.FilterClicked,
                Start = start,
                End = perPage
            };
            var listEmailDetails = await emailService.GetEmailDetailByEmailIdAsync(criteria);
            var paging = new PagingModel
            {
                CurrentPage = param.Page,
                PageSize = perPage,
                SearchValue = param.Search,
                TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)perPage)
            };
            var model = new EmailDetailViewModel
            {
                ListEmailDetails = listEmailDetails,
                Paging = paging
            };
            return PartialView("_ListEmailDetailData", model);
        }

        public async Task<ActionResult> ResendEmail(int userId, int emailId)
        {
            var email = await emailService.GetEmailDetailAsync(new GetDetailEmailDetailCriteria { UserId = userId, EmailId = emailId });
            var resultSendMail = await emailService.SendMailWithTemplateAsync(email.EmailTitle, email.EmailContent, email.EmailAddress, SendMailTemplates);
            await emailService.UpdateEmailStatusAsync(new UpdateEmailStatusCommand { Status = resultSendMail.Code, _EmailId = emailId, _UserId = userId });
            return Json(resultSendMail);
        }

        private static string EnsureCorrectFilename(string filename)
        {
            if (filename.Contains("\\"))
            {
                filename = filename[(filename.LastIndexOf("\\", StringComparison.OrdinalIgnoreCase) + 1)..];
            }

            return filename;
        }

        private string GetPathAndFilename(string filename) => pathService.MapAbsoluteRootPath("//uploads//" + filename);

    }
}