namespace SabaNews.Livescore.Provider.API.Models
{
    internal class HttpClientConfigurations
    {
        public const string ConfigurationKey = "HttpClientConfigurations";

        public bool IsUseProxy { get; set; }

        public string Proxy { get; set; }

        public bool ByPassLocal { get; set; }
    }
}