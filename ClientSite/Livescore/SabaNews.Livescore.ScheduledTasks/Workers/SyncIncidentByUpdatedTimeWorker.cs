namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class SyncIncidentByUpdatedTimeWorker : WorkerBase
    {
        private readonly ISyncMatchService syncMatchService;

        public SyncIncidentByUpdatedTimeWorker(
            ISyncMatchService syncMatchService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncMatchService = syncMatchService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncIncidentByUpdatedTime);
        }

        public override async Task DoWorkAsync()
        {
            await syncMatchService.SyncIncidentByUpdatedTimeAsync();
        }
    }
}
