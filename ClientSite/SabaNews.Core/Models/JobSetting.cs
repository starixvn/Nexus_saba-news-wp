namespace SabaNews.Core.Models
{
    public class JobSetting
    {
        public string JobName { get; set; }

        public string CronExpression { get; set; }

        public bool IsEnabled { get; set; }
    }
}