namespace SabaNews.Core.Models
{
    public class UserSession
    {
        public int Id { get; set; }

        public string Email { get; set; }

        public string Name { get; set; }

        public string Password { get; set; }

        public bool EmailConfirmed { get; set; }

        public string LoginType { get; set; }
    }
}
