namespace SabaNews.Models.Auth
{
    using Microsoft.AspNetCore.Identity;

    public class GoogleUser : IdentityUser
    {
        public string DisplayName { get; set; }
    }
}