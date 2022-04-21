namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncSeasonStandingsByLastUpdatedTimeWorker : WorkerBase
    {
        private readonly ISyncSeasonService syncSeasonService;

        public SyncSeasonStandingsByLastUpdatedTimeWorker(
            ISyncSeasonService syncSeasonService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncSeasonService = syncSeasonService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncSeasonStandingsByLastUpdatedTime);
        }

        public override async Task DoWorkAsync()
        {
            await syncSeasonService.SyncSeasonStandingsByLastUpdatedTimeAsync();
        }
    }
}