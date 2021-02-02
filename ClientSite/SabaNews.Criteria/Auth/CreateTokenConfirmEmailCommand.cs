namespace SabaNews.Criteria.Auth
{
    public class CreateTokenConfirmEmailCommand : BaseNonQueryCommand
    {
        public int UserId { get; set; }

        public string Token { get; set; }

        public override string GetSettingKey() => "user_create_token_confirm";

        public override bool IsValid() => UserId > 0 && !string.IsNullOrEmpty(Token);
    }
}
