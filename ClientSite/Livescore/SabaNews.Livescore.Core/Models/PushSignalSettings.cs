namespace SabaNews.Livescore.Core.Models
{
    public class PushSignalSettings
    {
        public const string ConfigurationKey = "PushSignalSettings";
        public string Endpoint { get; set; }
        public string UpdateLiveMatchMethod { get; set; }
        public string UpdateLineupMethod { get; set; }
        public string UpdateStatisticMethod { get; set; }
        public string UpdateIncidentMethod { get; set; }
    }
}