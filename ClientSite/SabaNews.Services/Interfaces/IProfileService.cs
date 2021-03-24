namespace SabaNews.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;

    public interface IProfileService
    {
        Task<CommandResult> UpdateUserSavePostAsync(int userID, string listPosts);

        Task<UserSession> GetUserByIdAsync(int userID);

        Task<CommandResult> UpdateUserInfoAsync(UserSession user);
    }
}
