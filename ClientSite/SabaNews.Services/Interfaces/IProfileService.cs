namespace SabaNews.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Auth;
    using SabaNews.Criteria.User;

    public interface IProfileService
    {
        Task<CommandResult> UpdateUserSavePostAsync(int userID, string listPosts);

        Task<UserSession> GetUserByIdAsync(int userID);

        Task<CommandResult> UpdateUserInfoAsync(UserSession user);

        Task<CommandResult> DisconnectLoginTypeAsync(int userId, string loginType);

        Task<IEnumerable<UserSession>> GetAllUserAsync(GetAllUserCriteria criteria);

        Task<IEnumerable<UserSession>> GetUserReportAsync(GetUserReportCriteria criteria);

        Task<CommandResult> UnSubcriteUserAsync(int UserId, string token);
    }

}
