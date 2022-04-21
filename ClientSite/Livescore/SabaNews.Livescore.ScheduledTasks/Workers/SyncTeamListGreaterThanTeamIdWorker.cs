namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncTeamListGreaterThanTeamIdWorker : WorkerBase
    {
        private readonly ISyncTeamService syncTeamService;

        public SyncTeamListGreaterThanTeamIdWorker(
            ISyncTeamService syncTeamService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncTeamService = syncTeamService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncTeamListGreaterThanTeamId);
        }

        public override async Task DoWorkAsync()
        {
            await syncTeamService.SyncTeamListGreaterThanTeamIdAsync();
        }
    }
}