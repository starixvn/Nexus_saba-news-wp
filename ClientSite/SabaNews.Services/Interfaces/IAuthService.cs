namespace SabaNews.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Models.Auth;

    public interface IAuthService
    {
        Task<UserSession> GetUserAsync(string email);

        Task<CommandResult> CreateUserAsync(RegisterModel model);

        bool ComparePassword(string plainText, string encrypt);

        Task<CommandResult> CreateTokenConfirmEmail(string email, string token);

        Task<CommandResult> ConfirmEmailAsync(int userId, string token);

        Task<CommandResult> CreateTokenResetPassword(string email, string token);

        Task<CommandResult> VerifyTokenResetPasswordAsync(int userId, string token);

        Task<CommandResult> ResetPasswordAsync(ResetPasswordModel model);

        Task SendMailConfirmationAsync(string email, string url);

        Task SendMailResetPasswordAsync(string email, string url);

        Task<CommandResult> SendMailAsync(string subject, string body, string receiverEmail);

        Task<CommandResult> SendMailEventAsync(string subject, string content, string email);
    }
}
