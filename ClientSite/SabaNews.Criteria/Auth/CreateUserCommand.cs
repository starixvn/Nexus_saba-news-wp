namespace SabaNews.Criteria
{
    public class CreateUserCommand : BaseNonQueryCommand
    {
        public string _Name { get; set; }

        public string _Email { get; set; }

        public string _Password { get; set; }

        public int UserId { get; set; }

        public override string GetSettingKey() => "user_create";

        public override bool IsValid() => !string.IsNullOrEmpty(_Name) && !string.IsNullOrEmpty(_Email) && !string.IsNullOrEmpty(_Password);
    }
}
