namespace SabaNews.Services
{
    using System.Threading.Tasks;
    using BCrypt.Net;
    using SabaNews.Core.Models;
    using SabaNews.Criteria;
    using SabaNews.Criteria.Auth;
    using SabaNews.Models.Auth;
    using SabaNews.Services.Interfaces;

    public class AuthService : IAuthService
    {
        private readonly IBaseService baseService;

        public AuthService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<User> GetUserAsync(string email)
        {
            var criteria = new GetUserLoginCriteria
            {
                Email = email
            };
            return await baseService.GetDataEntityAsync<User>(criteria);
        }

        public async Task<CommandResult> CreateUserAsync(RegisterModel model)
        {
            CreateUserCommand command = new CreateUserCommand
            {
                _Email = model.Email,
                _Name = model.Name,
                _Password = BCrypt.HashPassword(model.Password)
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public bool ComparePassword(string plainText, string encrypt)
        {
            return BCrypt.Verify(plainText, encrypt);
        }
    }
}
