namespace SabaNews.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Models.Auth;

    public interface IAuthService
    {
        Task<User> GetUserAsync(string email);

        Task<CommandResult> CreateUserAsync(RegisterModel model);

        bool ComparePassword(string plainText, string encrypt);

        Task<string> CreateTokenConfirmEmail(int userId);

        Task<CommandResult> ConfirmEmailAsync(int userId, string token);

        Task<string> CreateTokenResetPassword(string email);

        Task<CommandResult> VerifyTokenResetPasswordAsync(int userId, string token);
    }
}
