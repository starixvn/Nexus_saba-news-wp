namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncTeamWorker : BaseWorker
    {
        private readonly ISyncTeamService syncTeamService;

        public SyncTeamWorker(
            ISyncTeamService syncTeamService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncTeamService = syncTeamService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncTeamList);
        }

        public override async Task ExecuteJobAsync()
        {
            await syncTeamService.SyncTeamListAsync();
        }
    }
}