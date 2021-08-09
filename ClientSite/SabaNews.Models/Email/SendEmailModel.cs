namespace SabaNews.Models.Email
{
    public class SendEmailModel
    {
        public string EmailAddress { get; set; }

        public string EmailTitle { get; set; }

        public string EmailContent { get; set; }

        public string EmailTemplate { get; set; }

        public string SubscribeLink { get; set; }
    }
}
