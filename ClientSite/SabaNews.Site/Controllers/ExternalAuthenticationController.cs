namespace SabaNews.Site.Controllers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Security.Claims;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Authentication;
    using Microsoft.AspNetCore.Authentication.Cookies;
    using Microsoft.AspNetCore.Authentication.Google;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Models.Auth;
    using SabaNews.Services.Interfaces;

    public class ExternalAuthenticationController : Controller
    {
        private readonly IExternalAuthService externalAuthService;
        private readonly ISessionService sessionService;

        public ExternalAuthenticationController(
            IExternalAuthService externalAuthService,
            ISessionService sessionService)
        {
            this.externalAuthService = externalAuthService;
            this.sessionService = sessionService;
        }

        public IActionResult Login(string provider = GoogleDefaults.AuthenticationScheme, string returnUrl = null)
        {
            string redirectUrl = Url.Action("ExternalResponse", "ExternalAuthentication", new { provider, returnUrl }, Request.Scheme);
            var properties = new AuthenticationProperties { RedirectUri = redirectUrl };

            return Challenge(properties, provider);
        }

        public async Task<IActionResult> ExternalResponse(string provider, string returnUrl)
        {
            var result = await HttpContext.AuthenticateAsync(CookieAuthenticationDefaults.AuthenticationScheme);
            var claims = result.Principal.Identities.FirstOrDefault().Claims;
            var userInfo = new ExternalUser
            {
                Identifier = claims.FirstOrDefault(c => c.Type == ClaimTypes.NameIdentifier)?.Value,
                FirstName = claims.FirstOrDefault(c => c.Type == ClaimTypes.GivenName)?.Value,
                LastName = claims.FirstOrDefault(c => c.Type == ClaimTypes.Surname)?.Value,
                DisplayName = claims.FirstOrDefault(c => c.Type == ClaimTypes.Name)?.Value,
                Email = claims.FirstOrDefault(c => c.Type == ClaimTypes.Email)?.Value,
                Avatar = claims.FirstOrDefault(c => c.Type == ClaimTypes.Thumbprint).Value,
                LoginType = provider
            };

            await SetSessionUserAsync(userInfo);

            await DoLoginAsync(userInfo.DisplayName, userInfo.Identifier);
            if (!string.IsNullOrWhiteSpace(returnUrl))
            {
                return Redirect(returnUrl);
            }

            return Redirect(returnUrl);
        }

        private async Task SetSessionUserAsync(ExternalUser userInfo)
        {
            UserSession user = await externalAuthService.GetUserAsync(userInfo.Identifier, userInfo.LoginType);
            if (user != null)
            {
                sessionService.User = user;
            }
            else
            {
                CommandResult result = await externalAuthService.CreateUserAsync(userInfo);
                if (result.IsSuccessful)
                {
                    sessionService.User = new UserSession
                    {
                        Email = userInfo.Email,
                        EmailConfirmed = true,
                        Id = userInfo.Id,
                        LoginType = userInfo.LoginType,
                        Name = userInfo.DisplayName,
                        Photo = userInfo.Avatar
                    };
                }
            }
        }

        private async Task DoLoginAsync(string name, string identify)
        {
            var claims = new List<Claim>
            {
                new Claim(ClaimTypes.Name, name),
                new Claim(ClaimTypes.NameIdentifier, identify)
            };

            var identity = new ClaimsIdentity(claims, CookieAuthenticationDefaults.AuthenticationScheme);
            await HttpContext.SignInAsync(CookieAuthenticationDefaults.AuthenticationScheme, new ClaimsPrincipal(identity));
        }
    }
}