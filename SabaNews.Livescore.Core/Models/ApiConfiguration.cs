namespace SabaNews.Livescore.Core.Models
{
    public class ApiConfiguration
    {
        public const string ConfigurationKeyName = "ApiConfiguration";

        public string Endpoint { get; set; }

        public string ApiKey { get; set; }
    }
}