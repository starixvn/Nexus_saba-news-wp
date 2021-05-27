namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using Hangfire;
    using Microsoft.Extensions.Options;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Services.Interfaces;

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

            if (IsEnabledJob(HangfireJobName.RefreshSitemapXmlFile))
            {
                recurringJob.AddOrUpdate<IRefreshCachingService>("Update sitemap data",
                            job => job.UpdateSitemapData(),
                            GetCronExpression(HangfireJobName.RefreshSitemapXmlFile),
                            TimeZoneInfo.Local);
            }

            if (IsEnabledJob(HangfireJobName.RefreshNewsSitemapXmlFile))
            {
                recurringJob.AddOrUpdate<IRefreshCachingService>("Update news sitemap data",
                            job => job.UpdateNewsSitemapData(),
                            GetCronExpression(HangfireJobName.RefreshNewsSitemapXmlFile),
                            TimeZoneInfo.Local);
            }

            if (IsEnabledJob(HangfireJobName.RefreshRSS))
            {
                recurringJob.AddOrUpdate<IRefreshCachingService>("Update RSS",
                            job => job.UpdateRSSData(),
                            GetCronExpression(HangfireJobName.RefreshRSS),
                            TimeZoneInfo.Local);
            }
            if (IsEnabledJob(HangfireJobName.RefreshEuro2020PageCaching))
            {
                recurringJob.AddOrUpdate<IRefreshCachingService>("Update Euro2020 home page data caching",
                            job => job.UpdateEuro2020PageCaching(),
                            GetCronExpression(HangfireJobName.RefreshEuro2020PageCaching),
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