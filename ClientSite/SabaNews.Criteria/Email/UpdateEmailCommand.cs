namespace SabaNews.Criteria.Email
{
    using System;

    public class UpdateEmailCommand : BaseNonQueryCommand
    {
        public int EmailId { get; set; }

        public string EmailTitle { get; set; }

        public string EmailContent { get; set; }

        public string EmbeddedLink { get; set; }

        public DateTime SendDate { get; set; }

        public int CreatedBy { get; set; }

        public int Status { get; set; }

        public override string GetSettingKey() => "email_update";

        public override bool IsValid() => EmailId > 0;
    }
}
