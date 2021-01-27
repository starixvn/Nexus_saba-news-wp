namespace SabaNews.Site.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Models.Auth;

    public class AuthController : Controller
    {
        public IActionResult Index()
        {
            return PartialView("_Index");
        }

        [HttpPost]
        public IActionResult Login(LoginModel model)
        {
            return Json(null);
        }

        [HttpPost]
        public IActionResult Register(RegisterModel model)
        {
            return Json(null);
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
    }
}
