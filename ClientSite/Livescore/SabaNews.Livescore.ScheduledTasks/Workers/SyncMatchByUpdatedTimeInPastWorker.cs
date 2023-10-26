namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncMatchByUpdatedTimeInPastWorker : WorkerBase
    {
        private readonly ISyncMatchService syncMatchService;
        private readonly AppSettings appSettings;

        public SyncMatchByUpdatedTimeInPastWorker(
            ISyncMatchService syncMatchService,
            List<ScheduledTaskSettings> jobSettings,
            AppSettings appSettings,
            ILogServiceProvider logService)
                : base(logService)
        {
            this.syncMatchService = syncMatchService;
            this.appSettings = appSettings;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncMatchByUpdatedTimeInPast);
        }

        public override async Task DoWorkAsync()
        {
            var endTime = DateTimeUtilize.UtcNow().Date.AddSeconds(-1);
            var startTime = endTime.AddDays(appSettings.NumOfDayPastToSyncMatch);

            var lastUpdatedTime = await syncMatchService.GetMatchLastUpdatedTimeInDateRangeAsync(startTime, endTime, true);
            await syncMatchService.SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(startTime, endTime, lastUpdatedTime);
        }
    }
}