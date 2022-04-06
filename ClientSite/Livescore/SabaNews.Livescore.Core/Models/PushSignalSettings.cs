namespace SabaNews.Livescore.Core.Models
{
    public class PushSignalSettings
    {
        public const string ConfigurationKey = "PushSignalSettings";
        public string Endpoint { get; set; }
        public string UpdateLiveMatchMethod { get; set; }
        public string UpdateLiveMatchDetail { get; set; }
    }
}