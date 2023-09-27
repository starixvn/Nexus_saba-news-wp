namespace SabaNews.Livescore.ScheduledTasks.Models
{
    public class ScheduledTaskSettings
    {
        public const string ConfigurationKey = "JobSettings";

        public string Name { get; set; }

        public bool Enabled { get; set; }

        public double Interval { get; set; }
    }
}