namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncPlayerListByLastUpdatedTimeWorker : WorkerBase
    {
        private readonly ISyncPlayerService syncPlayerService;

        public SyncPlayerListByLastUpdatedTimeWorker(
            ISyncPlayerService syncPlayerService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncPlayerService = syncPlayerService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncPlayerListByLastUpdatedTime);
        }

        public override async Task DoWorkAsync()
        {
            await syncPlayerService.SyncPlayerListByLastUpdatedTimeAsync();
        }
    }
}