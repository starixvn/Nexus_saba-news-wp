namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Auth;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

    public class ProfileService : IProfileService
    {
        private readonly IBaseService baseService;

        public ProfileService(IBaseService baseService)
        {
            this.baseService = baseService;
        }
        public async Task<CommandResult> UpdateUserSavePostAsync(int userID, string listPosts)
        {
            var command = new UpdateUserSavePostCommand
            {
                UserID = userID,
                ListPosts = listPosts
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<UserSession> GetUserByIdAsync(int userID)
        {
            var criteria = new GetUserByIdCriteria
            {
                UserId = userID
            };
            return await baseService.GetDataEntityAsync<UserSession>(criteria);
        }
    }
}
