namespace SabaNews.Services
{
    using System.Threading.Tasks;
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
    }
}
