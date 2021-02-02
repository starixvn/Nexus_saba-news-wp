namespace SabaNews.Models.Auth
{
    using System.ComponentModel.DataAnnotations;

    public class ResetPasswordModel
    {
        [Required]
        public string Password { get; set; }

        [Compare("Password")]
        [Required]
        public string ConfirmPassword { get; set; }

        [Required]
        public int Id { get; set; }
    }
}
