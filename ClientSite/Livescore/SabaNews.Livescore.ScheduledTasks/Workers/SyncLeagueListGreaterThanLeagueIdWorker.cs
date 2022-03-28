namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncLeagueListGreaterThanLeagueIdWorker : BaseWorker
    {
        private readonly ISyncLeagueService syncLeagueService;

        public SyncLeagueListGreaterThanLeagueIdWorker(
            ISyncLeagueService syncLeagueService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncLeagueService = syncLeagueService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncLeagueListGreaterThanLeagueId);
        }

        public override async Task ExecuteJobAsync()
        {
            await syncLeagueService.SyncLeagueListGreaterThanLeagueIdAsync();
        }
    }
}