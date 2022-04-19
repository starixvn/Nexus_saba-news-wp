namespace SabaNews.Livescore.Provider.API.Models
{
    public class ApiConfiguration
    {
        public const string ConfigurationKeyName = "ApiConfiguration";
        public string Endpoint { get; set; }
        public string Project { get; set; }
        public string Secret { get; set; }
        public string LoginPath { get; set; }
        public bool IsWriteLogRequest { get; set; }
        public bool IsWriteResponse { get; set; }
    }
}