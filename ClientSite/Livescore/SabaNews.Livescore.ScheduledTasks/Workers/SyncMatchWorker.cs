namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services.Interfaces;

    internal class SyncMatchWorker : BaseWorker
    {
        private readonly ISyncMatchService syncMatchService;

        public SyncMatchWorker(ISyncMatchService syncMatchService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService)
                : base(logService)
        {
            this.syncMatchService = syncMatchService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncAllMatchByUpdatedTime);
        }

        public override async Task ExecuteJobAsync()
        {
            await syncMatchService.SyncAllMatchAsync();
        }
    }
}