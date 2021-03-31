namespace SabaNews.Site.Controllers
{
    using System;
    using System.Collections.Generic;
    using System.Security.Claims;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Mvc;
    using Newtonsoft.Json;
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
            UserSession user = await authService.GetUserAsync(model.Email);
            if (user != null && !string.IsNullOrEmpty(user.Password) && authService.ComparePassword(model.Password, user.Password))
            {
                await DoLoginAsync(user.Name, user.Email);
                user.LoginType = LoginType.Internal;
                sessionService.User = user;
                return Json(new CommandResult { Code = 0, Message = string.Empty });
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
                var token = Guid.NewGuid().ToString();
                commandResult = await authService.CreateTokenConfirmEmail(model.Email, token);
                if (!commandResult.IsSuccessful)
                {
                    return Json(commandResult);
                }
                string url = Url.Action("ConfirmEmail", "Auth", new { UserId = model.Id, token }, Request.Scheme);
                await authService.SendMailConfirmationAsync(model.Email, url);
                //await DoLoginAsync(model.Name, model.Email);
                //sessionService.User = new UserSession
                //{
                //    Id = model.Id,
                //    Email = model.Email,
                //    Name = model.Name,
                //    EmailConfirmed = false,
                //    LoginType = LoginType.Internal
                //};
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
            var token = Guid.NewGuid().ToString();
            CommandResult commandResult = await authService.CreateTokenResetPassword(email, token);
            if (!commandResult.IsSuccessful)
            {
                return Json(commandResult);
            }
            UserSession user = await authService.GetUserAsync(email);
            string url = Url.Action("ResetPassword", "Auth", new { UserId = user.Id, token }, Request.Scheme);
            await authService.SendMailResetPasswordAsync(user.Email, url);
            return Json(commandResult);
        }

        [HttpGet]
        public async Task<IActionResult> ResetPassword(int userId, string token)
        {
            CommandResult commandResult = await authService.VerifyTokenResetPasswordAsync(userId, token);
            ViewBag.UserId = userId;
            ViewBag.Token = token;
            return View(commandResult.IsSuccessful);
        }

        [HttpPost]
        public async Task<IActionResult> ResetPassword(ResetPasswordModel model)
        {
            CommandResult commandResult = await authService.ResetPasswordAsync(model);
            return Json(commandResult);
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
        }

    }
}
