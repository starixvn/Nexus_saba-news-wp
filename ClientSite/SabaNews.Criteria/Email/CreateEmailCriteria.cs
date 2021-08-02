using System;

namespace SabaNews.Criteria.Email
{
    public class CreateEmailCriteria : BaseNonQueryCommand
    {
        public int Id { get; set; }
       
        public string EmailTitle { get; set; }

        public string EmailContent { get; set; }

        public string EmailEmbeddedLink { get; set; }

        public DateTime SendDate { get; set; }

        public int CreatedBy { get; set; }

        public int Status { get; set; }
        
        public DateTime CreatedDate { get; set; }

        public override string GetSettingKey() => "email_create";

        public override bool IsValid() => true;

    }
}
