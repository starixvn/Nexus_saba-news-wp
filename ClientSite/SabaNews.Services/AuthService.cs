namespace SabaNews.Services
{
    using System.IO;
    using System.Threading.Tasks;
    using BCrypt.Net;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.Extensions.Hosting;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria;
    using SabaNews.Criteria.Auth;
    using SabaNews.Models.Auth;
    using SabaNews.Services.Interfaces;

    public class AuthService : IAuthService
    {
        private const string ConfirmEmailTemplates = "EmailTemplates//ConfirmEmail.txt";
        private const string ResetPasswordTemplates = "EmailTemplates//ResetPassword.txt";
        private const string SendMailEventTemplates = "EmailTemplates//SendMailEvent.txt";

        private readonly IBaseService baseService;
        private readonly IWebHostEnvironment webHostEnvironment;
        private readonly IBaseMailService mailService;

        public AuthService(
            IBaseService baseService,
            IWebHostEnvironment webHostEnvironment,
            IBaseMailService mailService)
        {
            this.baseService = baseService;
            this.webHostEnvironment = webHostEnvironment;
            this.mailService = mailService;
        }

        public async Task<UserSession> GetUserAsync(string email)
        {
            var criteria = new GetUserLoginCriteria
            {
                Email = email
            };
            return await baseService.GetDataEntityAsync<UserSession>(criteria);
        }

        public async Task<CommandResult> CreateUserAsync(RegisterModel model)
        {
            CreateUserCommand command = new CreateUserCommand
            {
                _Email = model.Email,
                _Name = model.Name,
                _Password = BCrypt.HashPassword(model.Password)
            };
            CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            model.Id = command.UserId;
            return result;
        }

        public bool ComparePassword(string plainText, string encrypt)
        {
            return BCrypt.Verify(plainText, encrypt);
        }

        public async Task<CommandResult> CreateTokenConfirmEmail(string email, string token)
        {
            CreateTokenConfirmEmailCommand command = new CreateTokenConfirmEmailCommand
            {
                Email = email,
                Token = token
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> ConfirmEmailAsync(int userId, string token)
        {
            ConfirmEmailCommand command = new ConfirmEmailCommand
            {
                Id = userId,
                Token = token
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> CreateTokenResetPassword(string email, string token)
        {
            CreateTokenResetPasswordCommand command = new CreateTokenResetPasswordCommand
            {
                Email = email,
                Token = token
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> VerifyTokenResetPasswordAsync(int userId, string token)
        {
            VerifyTokenResetPasswordCommand command = new VerifyTokenResetPasswordCommand
            {
                Id = userId,
                Token = token
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> ResetPasswordAsync(ResetPasswordModel model)
        {
            ResetPasswordCommand command = new ResetPasswordCommand
            {
                Id = model.Id,
                Token = model.Token,
                _Password = BCrypt.HashPassword(model.Password)
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task SendMailConfirmationAsync(string email, string url)
        {
            var mailPath = Path.Combine(webHostEnvironment.ContentRootPath, ConfirmEmailTemplates);
            const string subject = Constants.SubjectConfirmation;
            var body = string.Format(
                mailService.ReadTextFile(mailPath),
                email,
                url);
            await SendMailAsync(subject, body, email);
        }

        public async Task SendMailResetPasswordAsync(string email, string url)
        {
            var mailPath = Path.Combine(webHostEnvironment.ContentRootPath, ResetPasswordTemplates);
            const string subject = Constants.SubjectResetPassword;
            var body = string.Format(
                mailService.ReadTextFile(mailPath),
                email,
                url);
            await SendMailAsync(subject, body, email);
        }

        public async Task<CommandResult> SendMailEventAsync(string subject, string content, string email)
        {
            var mailPath = Path.Combine(webHostEnvironment.ContentRootPath, SendMailEventTemplates);
            var rawBody = mailService.ReadTextFile(mailPath);
            var body = rawBody.Replace("{0}", content);
            return await SendMailAsync(subject, body, email);
        }

        public async Task<CommandResult> SendMailAsync(string subject, string body, string receiverEmail)
        {
            var email = new MailModel
            {
                Subject = subject,
                Body = body,
                ReceiverEmail = receiverEmail
            };
            return await mailService.SendMailAsync(email);
        }

      
    }
}