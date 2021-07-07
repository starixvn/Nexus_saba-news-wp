namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Email;
    using SabaNews.Models.Email;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class EmailService : IEmailService
    {
        private readonly IBaseService baseService;

        public EmailService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<CommandResult> CreateEmailAsync(EmailModel model)
        {
            var command = new CreateEmailCriteria
            {
                EmailTitle = model.EmailTitle,
                EmailContent = model.EmailContent,
                EmailEmbeddedLink = model.EmailReturnLink,
                SendDate = model.SendDate,
                CreatedBy = model.CreatedBy
            };
            return await baseService.ExecuteNonQueryAsync(command);
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
                EmailAddress = model.EmailAddress
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }
    }
}
