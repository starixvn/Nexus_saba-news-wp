namespace SabaNews.Models.Auth
{
    using System.ComponentModel.DataAnnotations;

    public class ConfirmEmailModel
    {
        public string Email { get; set; }

        public string Token { get; set; }

        public long UserId { get; set; }
    }
}
