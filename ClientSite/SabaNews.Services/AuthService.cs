namespace SabaNews.Services
{
    using System;
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

        public async Task<UserSession> GetUserAsync(string email)
        {
            var criteria = new GetUserLoginCriteria
            {
                Email = email
            };
            return await baseService.GetDataEntityAsync<UserSession>(criteria);
        }

        public async Task<CommandResult> CreateUserAsync(RegisterModel model)
        {
            CreateUserCommand command = new CreateUserCommand
            {
                _Email = model.Email,
                _Name = model.Name,
                _Password = BCrypt.HashPassword(model.Password)
            };
            CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            model.Id = command.UserId;
            return result;
        }

        public bool ComparePassword(string plainText, string encrypt)
        {
            return BCrypt.Verify(plainText, encrypt);
        }

        public async Task<string> CreateTokenConfirmEmail(string email)
        {
            string token = Guid.NewGuid().ToString();
            CreateTokenConfirmEmailCommand command = new CreateTokenConfirmEmailCommand
            {
                Email = email,
                Token = token
            };
            CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            return result.IsSuccessful ? token : string.Empty;
        }

        public async Task<CommandResult> ConfirmEmailAsync(int userId, string token)
        {
            ConfirmEmailCommand command = new ConfirmEmailCommand
            {
                Id = userId,
                Token = token
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<string> CreateTokenResetPassword(string email)
        {
            string token = Guid.NewGuid().ToString();
            CreateTokenResetPasswordCommand command = new CreateTokenResetPasswordCommand
            {
                Email = email,
                Token = token
            };
            CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            return result.IsSuccessful ? token : string.Empty;
        }

        public async Task<CommandResult> VerifyTokenResetPasswordAsync(int userId, string token)
        {
            VerifyTokenResetPasswordCommand command = new VerifyTokenResetPasswordCommand
            {
                Id = userId,
                Token = token
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> ResetPasswordAsync(ResetPasswordModel model)
        {
            ResetPasswordCommand command = new ResetPasswordCommand
            {
                Id = model.Id,
                Token = model.Token,
                _Password = BCrypt.HashPassword(model.Password)
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }
    }
}
