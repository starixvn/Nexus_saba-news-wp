namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Email;
    using SabaNews.Models.Email;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class EmailService : IEmailService
    {
        private readonly IBaseService baseService;
        private readonly IBaseMailService baseMailService;
        private readonly IPathService pathService;

        public EmailService(
            IBaseService baseService,
            IBaseMailService baseMailService,
            IPathService pathService)
        {
            this.baseService = baseService;
            this.baseMailService = baseMailService;
            this.pathService = pathService;

        }

        public async Task<CommandResult> CreateEmailAsync(EmailModel model)
        {
            var command = new CreateEmailCriteria
            {
                EmailTitle = model.EmailTitle,
                EmailContent = model.EmailContent,
                EmbeddedLink = model.EmbeddedLink,
                SendDate = model.SendDate,
                CreatedBy = model.CreatedBy,
                Status = model.Status,
                CreatedDate = DateTime.Now
            };
            var result =  await baseService.ExecuteNonQueryAsync(command);
            model.Id = command.Id;
            return result;
        }

        public async Task<CommandResult> CreateEmailDetailAsync(EmailDetailModel model)
        {
            var command = new CreateEmailDetailCriteria
            {
                EmailId = model.EmailId,
                UserId = model.UserId,
                TokenId = model.TokenId,
                EmailTitle = model.EmailTitle,
                EmailContent = model.EmailContent,
                EmailAddress = model.EmailAddress,
                SendTime = model.SendTime,
                Status = model.Status,
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<IEnumerable<EmailModel>> GetEmailByUserAsync(GetEmailByUserCriteria criteria)
        {
            return await baseService.GetDataAsync<EmailModel>(criteria);
        }

        public async Task<IEnumerable<EmailModel>> GetEmailsAsync(GetEmailsCriteria criteria)
        {
            return await baseService.GetDataAsync<EmailModel>(criteria);
        }

        public async Task<IEnumerable<EmailDetailModel>> GetEmailDetailByEmailIdAsync(GetEmailDetailByEmailIdCriteria criteria)
        {
            return await baseService.GetDataAsync<EmailDetailModel>(criteria);
        }

        public async Task<CommandResult> VerifyTokenEmailDetailAsync(VerifyTokenEmailDetailCommand command)
        {
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> UpdateEmailStatusAsync(UpdateEmailStatusCommand command)
        {
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<EmailDetailModel> GetEmailDetailAsync(GetDetailEmailDetailCriteria criteria)
        {
            return await baseService.GetDataEntityAsync<EmailDetailModel>(criteria);
        }

        public async Task<EmailModel> GetEmailByIdAsync(GetEmailByIdCriteria criteria)
        {
            return await baseService.GetDataEntityAsync<EmailModel>(criteria);
        }

        public async Task<CommandResult> UpdateStatusAsync(UpdateStatusCommand criteria)
        {
            return await baseService.ExecuteNonQueryAsync(criteria);
        }

        public async Task<CommandResult> UpdateEmailAsync(EmailModel model)
        {
            var command = new UpdateEmailCommand
            {
                EmailId = model.Id,
                EmailTitle = model.EmailTitle,
                EmailContent = model.EmailContent,
                EmbeddedLink = model.EmbeddedLink,
                Status = model.Status,
                CreatedBy = model.CreatedBy,
                SendDate = model.SendDate
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> SendMailWithTemplateAsync(string subject, string content, string email, string path)
        {
            var mailPath = pathService.MapPathContentRoot(path);
            var rawBody = baseMailService.ReadTextFile(mailPath);
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
            return await baseMailService.SendMailAsync(email);
        }
    }
}
