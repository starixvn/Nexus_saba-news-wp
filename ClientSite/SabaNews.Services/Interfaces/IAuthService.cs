namespace SabaNews.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Models.Auth;

    public interface IAuthService
    {
        Task<User> GetUserAsync(string email);
    }
}
