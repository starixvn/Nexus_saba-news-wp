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
    }
}
