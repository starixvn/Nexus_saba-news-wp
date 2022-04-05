namespace SabaNews.Livescore.Core.Models
{
    public class PushSignalSettings
    {
        public const string ConfigurationKey = "PushSignalSettings";
        public string Endpoint { get; set; }
        public string PushMatchListUrl { get; set; }
        public string PushMatchDetailUrl { get; set; }
        public string PushMatchIncidentsUrl { get; set; }
    }
}