namespace SabaNews.Livescore.ScheduledTasks.Models
{
    public class ScheduledTaskSettings
    {
        public const string ConfigurationKey = "JobSettings";

        public string Name { get; set; }

        public bool Enabled { get; set; }

        public int Interval { get; set; }
    }
}