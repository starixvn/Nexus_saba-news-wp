namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Email;
    using SabaNews.Models.Email;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IEmailService
    {
        Task<CommandResult> CreateEmailAsync(EmailModel model);

        Task<CommandResult> CreateEmailDetailAsync(EmailDetailModel model);

        Task<CommandResult> VerifyTokenEmailDetailAsync(VerifyTokenEmailDetailCommand command);

        Task<IEnumerable<EmailModel>> GetEmailByUserAsync(GetEmailByUserCriteria criteria);

        Task<IEnumerable<EmailDetailModel>> GetEmailDetailByEmailIdAsync(GetEmailDetailByEmailIdCriteria criteria);

        Task<CommandResult> UpdateEmailDetailStatusAsync(UpdateEmailDetailStatusCommand command);

        Task<EmailDetailModel> GetDetailByEmailIdUserIdAsync(GetDetailEmailDetailCriteria criteria);
    }
}
