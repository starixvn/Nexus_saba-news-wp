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
        private readonly IAuthService authService;
        private const string SendMailTemplate = "EmailTemplates//SendMailEDM.txt";
        private readonly string baseUrl;
        public EmailController(
            IProfileService profileService,
            ISessionService sessionService,
            IPathService pathService,
            IEmailService emailService,
            IConfiguration configuration,
            IAuthService authService)
        {
            this.profileService = profileService;
            this.sessionService = sessionService;
            this.pathService = pathService;
            this.emailService = emailService;
            this.configuration = configuration;
            this.authService = authService;
            baseUrl = configuration.GetValue<string>("BaseUrl");
        }

        public async Task<ActionResult> Index()
        {
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria());
            var adminUsers = users.Where(item => !string.IsNullOrWhiteSpace(item.Metadata) && item.Metadata.Contains(Role.Administrator)).ToList();
            var model = new EmailFilterViewModel
            {
                FromDate = DateTime.Now.AddMonths(-6),
                ToDate = DateTime.Now,
                Users = adminUsers
            };
            return View(model);
        }

        public async Task<ActionResult> GetListEmailData(EmailFilterViewModel param)
        {
            var perPage = param.PageSize;
            var start = (param.Page - 1) * perPage;
            var criteria = new GetEmailsCriteria 
            {
                Search = param.Search ??= "", 
                FromDate = param.FromDate,
                ToDate = param.ToDate,
                Status = param.Status,
                CreatedBy = param.CreatedBy,
                End = perPage,
                Start = start 
            };
            var emails = await emailService.GetEmailsAsync(criteria);
            var paging = new PagingModel
            {
                CurrentPage = param.Page,
                PageSize = perPage,
                TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)param.PageSize),
            };
            var model = new EmailViewModel
            {
                Emails = emails,
                Paging = paging
            };
            return PartialView("_ListEmailData", model);
        }

        public async Task<ActionResult> Create()
        {
            var rawUrl = string.Concat($"{baseUrl}", "VerifySendEmailLink?{token}");
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria());
            ViewBag.rawUrl = rawUrl;
            return View(users);
        }

        [HttpPost]
        public async Task<ActionResult> SaveAndSendCreate(EmailModel model)
        {
            model.CreatedBy = sessionService.User.Id;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
            model.Status = Constants.EmailSent;
            var userNumber = 0;
            var result = await emailService.CreateEmailAsync(model);

            if (result.IsSuccessful)
            {
                userNumber = await Send(model);
            }
            var redirectLink = Url.Action("Index", Constants.Email);
            return Json(new { result, userNumber, redirectLink });
        }

        
        [HttpPost]
        public async Task<ActionResult> SaveCreate(EmailModel model)
        {
            model.CreatedBy = sessionService.User.Id;
            model.Status = Constants.EmailNew;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
            var result = await emailService.CreateEmailAsync(model);
            var redirectLink = Url.Action("Edit", Constants.Email, new { emailId = model.Id });
            return Json(new { result, redirectLink });
        }

        public async Task<ActionResult> Edit(int emailId)
        {
            var email = await emailService.GetEmailByIdAsync(new GetEmailByIdCriteria { Id = emailId });
            if (email == null || email.Status != Constants.EmailNew) {
                return Redirect(Url.Action("Index", Constants.Email));
            }
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria());
            var originalUrl = "";
            if (email.EmbeddedLink != null && email.EmbeddedLink.Contains(Constants.ReturnUrl)) {
                int indexString = email.EmbeddedLink.LastIndexOf(Constants.ReturnUrl, StringComparison.OrdinalIgnoreCase) + Constants.ReturnUrl.Length;
                originalUrl = email.EmbeddedLink[(indexString + 1)..];
            }
           
            var model = new EmailEditViewModel
            {
                Email = email,
                RawUrl = string.Concat($"{baseUrl}", "VerifySendEmailLink?{token}"),
                OriginalUrl = originalUrl,
                Users = users
            };
            

            return View(model);
        }

        public async Task<ActionResult> SaveAndSendEdit(EmailModel model)
        {
            var userNumber = 0;
            model.CreatedBy = sessionService.User.Id;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
            model.Status = Constants.EmailSent;
            var result = await emailService.UpdateEmailAsync(model);

            if (result.IsSuccessful)
            {
                userNumber = await Send(model);
            }
            return Json(new { result, userNumber });
        }

        [HttpPost]
        public async Task<ActionResult> SaveEdit(EmailModel model)
        {
            model.CreatedBy = sessionService.User.Id;
            model.Status = Constants.EmailNew;
            model.EmailContent = HttpUtility.HtmlDecode(model.EmailContent);
            var result = await emailService.UpdateEmailAsync(model);
            return Json(new { result });
        }


        [HttpPost]
        public async Task<ActionResult> TestSendEmail(EmailReviewModel model)
        {
            var sendEmailModel = new SendEDMModel
            {
                EmailAddress = model.EmailAddress,
                EmailTemplatePath = SendMailTemplate,
                EmailTitle = model.EmailTitle,
                EmailContent = model.EmailContent,
                SubscribeLink = "#",
                BaseUrl = baseUrl
            };
            var resultSendMail = await emailService.SendMailEDMWithTemplateAsync(sendEmailModel);
            return Json(resultSendMail);
        }

        [HttpPost]
        public async Task<ActionResult> SendEmail(int emailId)
        {
            var email = await emailService.GetEmailByIdAsync(new GetEmailByIdCriteria { Id = emailId });
            var result = await emailService.UpdateStatusAsync(new UpdateStatusCommand { EmailId = emailId, Status = Constants.EmailSent });
            var userNumber = 0;
            
            if (result.IsSuccessful)
            {
                userNumber = await Send(email);
            }
            return Json(new { result, userNumber });
        }

        [HttpPost]
        public async Task<ActionResult> Delete(int emailId)
        {
            var result = await emailService.UpdateStatusAsync(new UpdateStatusCommand { EmailId = emailId, Status = Constants.EmailDestroy });
            return Json(result);
        }

        [HttpPost]
        public async Task<ActionResult> UploadImageSendMail(IFormFile upload)
        {
            string filename = ContentDispositionHeaderValue.Parse(upload.ContentDisposition).FileName.Trim('"');
            filename = EnsureCorrectFilename(filename);
            filename = DateTime.Now.Millisecond + filename;
            string filePath = "";
            string error = "";
            try
            {
                using FileStream output = System.IO.File.Create(GetPathAndFilename(filename));
                await upload.CopyToAsync(output);
                filePath = output.Name;
            }
            catch (Exception ex)
            {
                error = ex.ToString();
            }
            string linkPhoto = "uploads/" + filename;
            string url = string.Concat(baseUrl, linkPhoto);
            return new JsonResult(new { path = url });
        }

        [HttpGet]
        public IActionResult FileBrowse()
        {
            var dir = new DirectoryInfo(pathService.MapAbsoluteRootPath("//uploads"));
            var model = new FileBrowseViewModel
            {
                FileInfos = dir.GetFiles(),
                BaseUrl = baseUrl
            };
            ViewBag.fileInfos = dir.GetFiles();
            return View("FileBrowse", model);
        }


        public async Task<ActionResult> Detail(int emailId)
        {
            var email = await emailService.GetEmailByIdAsync(new GetEmailByIdCriteria { Id = emailId });
            if (email == null)
            {
                return Redirect(Url.Action("Index", Constants.Email));
            }
            var emailFailedParam = new FilterEmailDetailCriteria
            {
                EmailID = emailId,
                Status = 1,
                Start = Constants.FromFirstItem,
                End = Constants.Total
            };
            var listEmailFailed = await emailService.FilterEmailDetailAsync(emailFailedParam);
            var model = new EmailDetailViewModel
            {
                Email = email,
                NumberEmailFailed = listEmailFailed.Count()
            };
            return View(model);
        }

        public async Task<ActionResult> GetListEmailDetailData(EmailDetailFilterViewModel param)
        {
            int perPage = param.PageSize;
            var start = (param.Page - 1) * perPage;
            var criteria = new FilterEmailDetailCriteria
            {
                EmailID = param.EmailId,
                Search = param.Search ??= "",
                IsClicked = param.FilterClicked,
                Status = param.Status,
                Start = start,
                End = perPage
            };
            var listEmailDetails = await emailService.FilterEmailDetailAsync(criteria);
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
            var unsubcribeLink = Url.Action(Constants.ConfirmUnsubscribe, Constants.ProfileAction, new { userId, token = email.SubscribeToken, Area = "" }, Request.Scheme);
            var sendEmailModel = new SendEDMModel
            {
                EmailAddress = email.EmailAddress,
                EmailTemplatePath = SendMailTemplate,
                EmailTitle = email.EmailTitle,
                EmailContent = email.EmailContent,
                SubscribeLink = unsubcribeLink,
                BaseUrl = baseUrl
            };
            var resultSendMail = await emailService.SendMailEDMWithTemplateAsync(sendEmailModel);
            await emailService.UpdateEmailStatusAsync(new UpdateEmailStatusCommand { Status = resultSendMail.Code, _EmailId = emailId, _UserId = userId });
            return Json(resultSendMail);
        }

        [HttpPost]
        public async Task<ActionResult> ResendAll(int emailId)
        {
            var param = new FilterEmailDetailCriteria
            {
                EmailID = emailId,
                Status = 1,
                Start = Constants.FromFirstItem,
                End = Constants.Total
            };
            var emails = await emailService.FilterEmailDetailAsync(param);
            var sentNumber = 0;
            foreach (var email in emails)
            {
                var unsubcribeLink = Url.Action(Constants.ConfirmUnsubscribe, Constants.ProfileAction, new { email.UserId, token = email.SubscribeToken, Area = "" }, Request.Scheme);
                var sendEmailModel = new SendEDMModel
                {
                    EmailAddress = email.EmailAddress,
                    EmailTemplatePath = SendMailTemplate,
                    EmailTitle = email.EmailTitle,
                    EmailContent = email.EmailContent,
                    SubscribeLink = unsubcribeLink,
                    BaseUrl = baseUrl
                };
                var resultSendMail = await emailService.SendMailEDMWithTemplateAsync(sendEmailModel);
                await emailService.UpdateEmailStatusAsync(new UpdateEmailStatusCommand { Status = resultSendMail.Code, _EmailId = emailId, _UserId = email.UserId });
                sentNumber = resultSendMail.IsSuccessful ? ++sentNumber : sentNumber;
            }
            return Json(sentNumber);
        }

        public async Task<int> Send(EmailModel model)
        {
            var users = await profileService.GetAllUserAsync(new GetAllUserCriteria { UserRole = Constants.CustomerRole });
            var subscribedUsers = users.Where(item => item.IsSubscribe == 1).ToList();
            var userNumber = subscribedUsers.Count;
            foreach (var user in subscribedUsers)
            {
                var token = Guid.NewGuid().ToString();
                var unsubcribeLink = Url.Action(Constants.ConfirmUnsubscribe, Constants.ProfileAction, new { userId = user.Id, Area = "" }, Request.Scheme);
                var createSubscribeTokenCommand = new CreateUserSubscribeTokenCommand { UserId = user.Id, Token = token };
                var createSubscribeTokenResult = await authService.CreateUserSubscribeToken(createSubscribeTokenCommand);
                if (createSubscribeTokenResult.IsSuccessful)
                {
                    var currentToken = createSubscribeTokenCommand.CurrentToken;
                    unsubcribeLink = Url.Action(Constants.ConfirmUnsubscribe, Constants.ProfileAction, new { userId = user.Id, token = currentToken, Area = "" }, Request.Scheme);
                }
                var emailContent = model.EmailContent.Replace("{token}", string.Concat("token=", token));
                var sendEmailModel = new SendEDMModel
                {
                    EmailAddress = user.Email,
                    EmailTemplatePath = SendMailTemplate,
                    EmailTitle = model.EmailTitle,
                    EmailContent = emailContent,
                    SubscribeLink = unsubcribeLink,
                    BaseUrl = baseUrl
                };
                var resultSendMail = await emailService.SendMailEDMWithTemplateAsync(sendEmailModel);
                var emailData = new EmailDetailModel
                {
                    EmailId = model.Id,
                    UserId = user.Id,
                    EmailAddress = user.Email,
                    EmailContent = emailContent,
                    EmailTitle = model.EmailTitle,
                    TokenId = token,
                    Status = resultSendMail.Code
                };
                await emailService.CreateEmailDetailAsync(emailData);
            }
            return userNumber;
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