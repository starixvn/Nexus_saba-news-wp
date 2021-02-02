namespace SabaNews.Site.Controllers
{
    using System.Collections.Generic;
    using System.Security.Claims;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Models.Auth;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;

    public class AuthController : Controller
    {
        private readonly IBaseService baseService;
        private readonly IAuthService authService;
        private readonly ISessionService sessionService;

        public AuthController(
            IBaseService baseService,
            IAuthService authService,
            ISessionService sessionService)
        {
            this.baseService = baseService;
            this.authService = authService;
            this.sessionService = sessionService;
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
                if (!string.IsNullOrEmpty(user.Password) && authService.ComparePassword(model.Password, user.Password))
                {
                    await DoLoginAsync(user.Name, user.Email);
                    return Json(new CommandResult { Code = 0, Message = string.Empty });
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
            commandResult = await authService.CreateUserAsync(model);
            if (commandResult.IsSuccessful)
            {
                string token = await authService.CreateTokenConfirmEmail(model.Id);
                if (!string.IsNullOrEmpty(token))
                {
                    //send mail
                }
                await DoLoginAsync(model.Name, model.Email);
            }
            return Json(commandResult);
        }

        [HttpGet]
        public async Task<IActionResult> ConfirmEmail(int userId, string token)
        {
            CommandResult commandResult = await authService.ConfirmEmailAsync(userId, token);
            return View(commandResult.IsSuccessful);
        }

        [HttpGet]
        public IActionResult ForgotPassword()
        {
            return PartialView("_ForgotPassword");
        }

        [HttpPost]
        public async Task<IActionResult> ForgotPassword(string email)
        {
            string token = await authService.CreateTokenResetPassword(email);
            return Json(null);
        }

        [HttpGet]
        public async Task<IActionResult> ResetPassword(int userId, string token)
        {
            CommandResult commandResult = await authService.VerifyTokenResetPasswordAsync(userId, token);
            ViewBag.UserId = userId;
            return View(commandResult.IsSuccessful);
        }

        [HttpPost]
        public IActionResult ResetPassword(ResetPasswordModel model)
        {
            return Json(null);
        }

        public async Task Logout()
        {
            sessionService.ClearAll();
            await HttpContext.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);
        }

        private async Task DoLoginAsync(string name, string email)
        {
            var claims = new List<Claim>
            {
                new Claim(ClaimTypes.Name, name),
                new Claim(ClaimTypes.Email, email)
            };

            var identity = new ClaimsIdentity(claims, CookieAuthenticationDefaults.AuthenticationScheme);
            await HttpContext.SignInAsync(CookieAuthenticationDefaults.AuthenticationScheme, new ClaimsPrincipal(identity));

            sessionService.User = new UserSession
            {
                Email = email,
                Name = name
            };
        }
    }
}
