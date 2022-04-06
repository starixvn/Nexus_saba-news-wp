namespace SabaNews.Livescore.Core.Models
{
    public class PushSignalSettings
    {
        public const string ConfigurationKey = "PushSignalSettings";
        public string Endpoint { get; set; }
        public string UpdateLiveSoccerUrl { get; set; }
        public string UpdateLiveMatchLineupUrl { get; set; }
        public string UpdateLiveMatchStatisticUrl { get; set; }
        public string UpdateLiveMatchIncidentUrl { get; set; }
    }
}