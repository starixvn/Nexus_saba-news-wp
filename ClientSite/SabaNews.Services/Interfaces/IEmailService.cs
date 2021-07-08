namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Models.Email;
    using System.Threading.Tasks;

    public interface IEmailService
    {
        Task<CommandResult> CreateEmailAsync(EmailModel model);

        Task<CommandResult> CreateEmailDetailAsync(EmailDetailModel model);
    }
}
