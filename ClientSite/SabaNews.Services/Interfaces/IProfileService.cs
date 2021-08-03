namespace SabaNews.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Auth;

    public interface IProfileService
    {
        Task<CommandResult> UpdateUserSavePostAsync(int userID, string listPosts);

        Task<UserSession> GetUserByIdAsync(int userID);

        Task<CommandResult> UpdateUserInfoAsync(UserSession user);

        Task<CommandResult> DisconnectLoginTypeAsync(int userId, string loginType);

        Task<IEnumerable<UserSession>> GetAllUserAsync(GetAllUserCriteria criteria);

        Task<CommandResult> UnSubcriteUserAsync(int UserId);
    }

}
