namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Auth;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
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

        public async Task<CommandResult> UpdateUserInfoAsync(UserSession user)
        {
            var command = new UpdateUserInfoCommand
            {
                UserID = user.Id,
                _name = user.Name,
                _password = user.Password,
                _phone = user.Phone,
                _gender = user.Gender,
                _photo = user.Photo
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<IEnumerable<UserSession>> GetAllUserAsync(GetAllUserCriteria criteria)
        {
            return await baseService.GetDataAsync<UserSession>(criteria);
        }

        public async Task<CommandResult> UnSubcriteUserAsync(int UserId)
        {
            var command = new UserUnsubribeCommand
            {
                UserId = UserId
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> DisconnectLoginTypeAsync(int userId, string loginType)
        {
            var command = new SocialDisconnectTypeCommand
            {
                UserId = userId,
                LoginType = loginType
            };
            CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            result.Message = command.NewLoginType;
            return result;
        }
    }
}
