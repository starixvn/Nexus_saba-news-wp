namespace SabaNews.Services
{
    using Hangfire;
    using Microsoft.Extensions.Options;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Linq;

    public class BackgroundJobService : IBackgroundJobService
    {
        private readonly IRecurringJobManager recurringJob;
        private readonly IList<JobSetting> jobSettings;

        public BackgroundJobService(IRecurringJobManager recurringJob, IOptions<List<JobSetting>> jobSettings)
        {
            this.recurringJob = recurringJob;
            this.jobSettings = jobSettings.Value;
        }

        public void TriggerUpdateCaching()
        {
            if (IsEnabledJob(HangfireJobName.RefreshHomePageCaching))
            {
                recurringJob.AddOrUpdate<IRefreshCachingService>("Update Home page data caching",
                              job => job.UpdateHomePageCaching(),
                              GetCronExpression(HangfireJobName.RefreshHomePageCaching),
                              TimeZoneInfo.Local);
            }
            if (IsEnabledJob(HangfireJobName.RefreshMenuCaching))
            {
                recurringJob.AddOrUpdate<IRefreshCachingService>("Update Menu data caching",
                             job => job.UpdateMenuCaching(),
                             GetCronExpression(HangfireJobName.RefreshMenuCaching),
                             TimeZoneInfo.Local);
            }

            if (IsEnabledJob(HangfireJobName.RefreshCategoryCaching))
            {
                recurringJob.AddOrUpdate<IRefreshCachingService>("Update category data caching",
                            job => job.UpdateCategoryCaching(),
                            GetCronExpression(HangfireJobName.RefreshCategoryCaching),
                            TimeZoneInfo.Local);
            }
        }

        private bool IsEnabledJob(string jobName)
        {
            var settings = jobSettings.FirstOrDefault(job => job.JobName.Equals(jobName, StringComparison.OrdinalIgnoreCase));

            return settings != null && settings.IsEnabled;
        }

        private string GetCronExpression(string jobName)
        {
            var settings = jobSettings.FirstOrDefault(job => job.JobName.Equals(jobName, StringComparison.OrdinalIgnoreCase));
            return settings != null ? settings.CronExpression : string.Empty;
        }
    }
}