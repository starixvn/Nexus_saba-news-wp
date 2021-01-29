namespace SabaNews.Models.Auth
{
    public class User
    {
        public string Email { get; set; }

        public string Name { get; set; }

        public string Password { get; set; }

        public bool EmailConfirmed { get; set; }
    }
}
