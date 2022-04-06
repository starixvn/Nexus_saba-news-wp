namespace SabaNews.Criteria.Auth
{
    public class VerifyTokenResetPasswordCommand : ConfirmEmailCommand
    {
        public override string GetSettingKey() => "user_verify_token_reset";
    }
}
