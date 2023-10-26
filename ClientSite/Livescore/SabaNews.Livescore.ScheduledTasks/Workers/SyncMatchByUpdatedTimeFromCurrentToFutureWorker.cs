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

    public class SyncMatchByUpdatedTimeFromCurrentToFutureWorker : WorkerBase
    {
        private readonly ISyncMatchService syncMatchService;
        private readonly AppSettings appSettings;

        public SyncMatchByUpdatedTimeFromCurrentToFutureWorker(
            ISyncMatchService syncMatchService,
            List<ScheduledTaskSettings> jobSettings,
            AppSettings appSettings,
            ILogServiceProvider logService)
                : base(logService)
        {
            this.syncMatchService = syncMatchService;
            this.appSettings = appSettings;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncMatchByUpdatedTimeFromCurrentToFuture);
        }

        public override async Task DoWorkAsync()
        {
            var startTime = DateTimeUtilize.UtcNow().Date;
            var endTime = startTime.AddDays(appSettings.NumOfDayFutureToSyncMatch + 1).AddSeconds(-1);

            var lastUpdatedTime = await syncMatchService.GetMatchLastUpdatedTimeInDateRangeAsync(startTime, endTime);

            await syncMatchService.SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(startTime, endTime, lastUpdatedTime);
        }
    }
}