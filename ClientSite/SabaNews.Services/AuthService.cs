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
            CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            model.Id = command.UserId;
            return result;
        }

        public bool ComparePassword(string plainText, string encrypt)
        {
            return BCrypt.Verify(plainText, encrypt);
        }

        public async Task<string> CreateTokenConfirmEmail(int userId)
        {
            string token = Guid.NewGuid().ToString();
            CreateTokenConfirmEmailCommand command = new CreateTokenConfirmEmailCommand
            {
                UserId = userId,
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
            //CreateTokenConfirmEmailCommand command = new CreateTokenConfirmEmailCommand
            //{
            //    UserId = userId,
            //    Token = token
            //};
            //CommandResult result = await baseService.ExecuteNonQueryAsync(command);
            //return result.IsSuccessful ? token : string.Empty;
            return token;
        }

        public async Task<CommandResult> VerifyTokenResetPasswordAsync(int userId, string token)
        {
            //ConfirmEmailCommand command = new ConfirmEmailCommand
            //{
            //    Id = userId,
            //    Token = token
            //};
            //return await baseService.ExecuteNonQueryAsync(command);
            CommandResult result = new CommandResult();
            return result;
        }

    }
}
