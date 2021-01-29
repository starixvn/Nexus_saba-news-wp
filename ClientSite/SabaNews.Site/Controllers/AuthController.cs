namespace SabaNews.Site.Controllers
{
    using System.Collections.Generic;
    using System.Security.Claims;
    using System.Threading.Tasks;
    using Fanex.Data;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Criteria;
    using SabaNews.Models.Auth;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;

    public class AuthController : Controller
    {
        private readonly IBaseService baseService;
        private readonly IAuthService authService;

        public AuthController(
            IBaseService baseService,
            IAuthService authService)
        {
            this.baseService = baseService;
            this.authService = authService;
        }

        public IActionResult Index()
        {
            return PartialView("_Index");
        }

        [HttpPost]
        public async Task<IActionResult> Login(LoginModel model)
        {
            User user = await authService.GetUserAsync(model.Email);
            if (user != null)
            {
                if (!string.IsNullOrEmpty(user.Password) && ComparePassword(model.Password, user.Password))
                {
                    return Json(new CommandResult { Code = 0, Message = string.Empty });
                    //await DoLoginAsync();
                }
            }
            return Json(new CommandResult { Code = 1, Message = Constants.WrongUsernameOrPassword });
        }

        [HttpPost]
        public async Task<IActionResult> Register(RegisterModel model)
        {
            CommandResult commandResult = new CommandResult
            {
                Message = Constants.InvalidData
            };
            if (!ModelState.IsValid)
            {
                return Json(commandResult);
            }
            CreateUserCommand command = new CreateUserCommand
            {
                _Email = model.Email,
                _Name = model.Name,
                _Password = Cryptography.EncryptPassword(model.Password)
            };
            commandResult = await baseService.ExecuteNonQueryAsync(command);
            //if (commandResult.IsSuccessful)
            //{
            //    // send mail for confirmation email
            //    // set session login 
            //    await DoLoginAsync();
            //}
            return Json(commandResult);
        }

        [HttpGet]
        public IActionResult ForgotPassword()
        {
            return PartialView("_ForgotPassword");
        }

        [HttpPost]
        public IActionResult ForgotPassword(string email)
        {
            return Json(null);
        }

        public async Task Logout()
        {
            await HttpContext.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);
        }

        private bool ComparePassword(string plainText, string encrypt)
        {
            string decrypt = Cryptography.DecryptPassword(encrypt);
            return string.Equals(plainText, decrypt);
        }

        private async Task DoLoginAsync()
        {
            var claims = new List<Claim>
            {
                new Claim(ClaimTypes.Name, string.Empty),
                new Claim(ClaimTypes.NameIdentifier, string.Empty),
                new Claim(ClaimTypes.Email, string.Empty)
            };

            var identity = new ClaimsIdentity(claims, CookieAuthenticationDefaults.AuthenticationScheme);
            await HttpContext.SignInAsync(CookieAuthenticationDefaults.AuthenticationScheme, new ClaimsPrincipal(identity));
        }
    }
}
