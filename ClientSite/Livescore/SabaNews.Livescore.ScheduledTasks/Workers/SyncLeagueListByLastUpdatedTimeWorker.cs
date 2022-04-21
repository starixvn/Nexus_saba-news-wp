namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncLeagueListByLastUpdatedTimeWorker : WorkerBase
    {
        private readonly ISyncLeagueService syncLeagueService;

        public SyncLeagueListByLastUpdatedTimeWorker(
            ISyncLeagueService syncLeagueService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncLeagueService = syncLeagueService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncLeagueListByLastUpdatedTime);
        }

        public override async Task DoWorkAsync()
        {
            await syncLeagueService.SyncLeagueListByLastUpdatedTimeAsync();
        }
    }
}