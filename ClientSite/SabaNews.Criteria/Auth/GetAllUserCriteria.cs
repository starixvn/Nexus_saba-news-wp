namespace SabaNews.Criteria.Auth
{
    using Fanex.Data.Repository;

    public class GetAllUserCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "user_get_all";

        public override bool IsValid() => true;
    }
}
