namespace SabaNews.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Auth;
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

        Task<CommandResult> CreateUserSubscribeToken(CreateUserSubscribeTokenCommand command);

        public void SendMailConfirmation(string email, string url);

        void SendMailResetPassword(string email, string url);

        CommandResult SendMail(string subject, string body, string receiverEmail);

        CommandResult SendMailEvent(string subject, string content, string email);
    }
}
