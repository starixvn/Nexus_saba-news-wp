namespace SabaNews.Services
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Auth;
    using SabaNews.Models.Auth;
    using SabaNews.Services.Interfaces;

    public class ExternalAuthService : IExternalAuthService
    {
        private readonly IBaseService baseService;

        public ExternalAuthService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<CommandResult> CreateUserAsync(ExternalUser user)
        {
            CreateSocialUserCommand command = new CreateSocialUserCommand
            {
                DisplayName = user.DisplayName,
                Email = user.Email,
                FirstName = user.FirstName,
                Identifier = user.Identifier,
                LastName = user.LastName,
                LoginType = user.LoginType
            };
            CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            user.Id = command.UserId;
            return result;
        }

        public async Task<UserSession> GetUserAsync(string email, string loginType)
        {
            var criteria = new GetSocialUserCriteria
            {
                Email = email,
                LoginType = loginType
            };
            return await baseService.GetDataEntityAsync<UserSession>(criteria);
        }
    }
}
