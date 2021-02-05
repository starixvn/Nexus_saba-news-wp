namespace SabaNews.Site.Controllers
{
    using System.Security.Claims;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Identity;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Models.Auth;

    public class ExternalAuthenticationController : Controller
    {
        private readonly SignInManager<GoogleUser> signInManager;

        public ExternalAuthenticationController(SignInManager<GoogleUser> signinMgr)
        {
            signInManager = signinMgr;
        }

        public IActionResult AccessDenied()
        {
            return View();
        }

        public IActionResult GoogleLogin()
        {
            string redirectUrl = Url.Action("GoogleResponse", "ExternalAuthentication");
            var properties = signInManager.ConfigureExternalAuthenticationProperties("Google", redirectUrl);
            return new ChallengeResult("Google", properties);
        }

        public async Task<IActionResult> GoogleResponse()
        {
            ExternalLoginInfo info = await signInManager.GetExternalLoginInfoAsync();
            if (info == null)
            {
                return RedirectToAction("Login", "Auth");
            }

            var result = await signInManager.ExternalLoginSignInAsync(info.LoginProvider, info.ProviderKey, false);
            string[] userInfo = { info.Principal.FindFirst(ClaimTypes.Name).Value, info.Principal.FindFirst(ClaimTypes.Email).Value };
            if (result.Succeeded)
            {
                return View(userInfo);
            }
            else
            {
                GoogleUser user = new GoogleUser
                {
                    Email = info.Principal.FindFirst(ClaimTypes.Email).Value,
                    UserName = info.Principal.FindFirst(ClaimTypes.Email).Value
                };

                return AccessDenied();
            }
        }
    }
}