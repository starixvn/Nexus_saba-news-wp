namespace SabaNews.Criteria.Auth
{
    public class CreateTokenResetPasswordCommand : CreateTokenConfirmEmailCommand
    {
        public override string GetSettingKey() => "user_create_token_reset";

    }
}
