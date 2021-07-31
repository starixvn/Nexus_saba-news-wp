namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
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

        public IActionResult Index()
        {
            return View();
        }

        public async Task<ActionResult> GetListEmailData(int page, string search)
        {
            const int perPage = Constants.Perpage;
            var start = (page - 1) * perPage;
            var criteria = new GetEmailsCriteria { Start = start, Search = search ??= "", End = perPage };
            var emails = await emailService.GetEmailsAsync(criteria);
            var paging = new PagingModel
            {
                CurrentPage = page,
                PageSize = perPage,
                TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)perPage),
                SearchValue = search
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
            var users = await profileService.GetAllUserAsync();
            var subscribedUsers = users.Where(item => item.IsSubcribe == 1).ToList();
            var userNumber = subscribedUsers.Count;
            model.CreatedBy = sessionService.User.Id;
            model.SendDate = DateTime.Now;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
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
                        EmailId = model.EmailId,
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

        public async Task<ActionResult> GetListEmailDetailData(int emailId, int page)
        {
            const int perPage = Constants.Perpage;
            var start = (page - 1) * perPage;
            var criteria = new GetEmailDetailByEmailIdCriteria
            {
                EmailID = emailId,
                Start = start,
                End = perPage
            };
            var listEmailDetails = await emailService.GetEmailDetailByEmailIdAsync(criteria);
            var paging = new PagingModel
            {
                CurrentPage = page,
                PageSize = perPage,
                TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)perPage),
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
            await emailService.UpdateEmailStatusAsync(new UpdateEmailStatusCommand { Status = resultSendMail.Code, EmailId = emailId, UserId = userId });
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