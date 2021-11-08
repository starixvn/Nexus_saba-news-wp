namespace SabaNews.Services
{
    using Newtonsoft.Json;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Logs;
    using SabaNews.Criteria.User;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;


    public class UserService : IUserService
    {
        private readonly IBaseService baseService;
        private readonly ILogService logService;
        private readonly ISessionService sessionService;

        public UserService(
            IBaseService baseService, 
            ILogService logService,
            ISessionService sessionService)
        {
            this.baseService = baseService;
            this.logService = logService;
            this.sessionService = sessionService;
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
                _photo = user.Photo,
                _isSubscribe = user.IsSubscribe
            };
            var oldData = sessionService.User;
            var logData = new WriteLogCommand
            {
                ActorId = user.Id,
                ActionId = LogAction.UpdateProfile,
                Data = JsonConvert.SerializeObject(new { user.Id, user.Name, user.Phone, user.Gender, user.Photo, user.IsSubscribe }),
                OldData = JsonConvert.SerializeObject(new { oldData.Id, oldData.Name, oldData.Phone, oldData.Gender, oldData.Photo, oldData.IsSubscribe })
            };
            var result = await baseService.ExecuteNonQueryAsync(command);
            await logService.WriteLog(logData);
            return result;
        }

        public async Task<IEnumerable<UserSession>> GetAllUserAsync(GetAllUserCriteria criteria)
        {
            return await baseService.GetDataAsync<UserSession>(criteria);
        }

        public async Task<IEnumerable<UserSession>> GetUserReportAsync(GetUserReportCriteria criteria)
        {
            return await baseService.GetDataAsync<UserSession>(criteria);
        }

        public async Task<CommandResult> UnSubcriteUserAsync(int UserId, string token)
        {
            var command = new UserUnsubribeCommand
            {
                UserId = UserId,
                Token = token
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
