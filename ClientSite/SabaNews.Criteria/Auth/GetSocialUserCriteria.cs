namespace SabaNews.Criteria.Auth
{
    public class GetSocialUserCriteria : GetUserLoginCriteria
    {
        public override string GetSettingKey() => "social_user_get_login";
    }
}
