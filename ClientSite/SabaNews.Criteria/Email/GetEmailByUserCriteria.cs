namespace SabaNews.Criteria.Email
{
    using Fanex.Data.Repository;

    public class GetEmailByUserCriteria : CriteriaBase
    {
        public int UserId { get; set; }

        public override string GetSettingKey() => "email_get_by_user";

        public override bool IsValid() => true;
    }
}
