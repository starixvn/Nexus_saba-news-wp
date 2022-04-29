namespace SabaNews.Livescore.Provider.API.Models
{
    public class HttpClientConfigurations
    {
        public const string ConfigurationKey = "HttpClientConfigurations";

        public bool IsUseProxy { get; set; }

        public string ProxyAddress { get; set; }

        public bool ByPassLocal { get; set; }
    }
}