namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncDeleledMatchesWorker : WorkerBase
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncMatchService syncMatchService;

        public SyncDeleledMatchesWorker(
            ISyncMatchService syncMatchService,
            IFetchMatchService fetchMatchService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService)
                : base(logService)
        {
            this.fetchMatchService = fetchMatchService;
            this.syncMatchService = syncMatchService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncDeletedMatchesInTwoWeek);
        }

        public override async Task DoWorkAsync()
        {
            var delMatches = await fetchMatchService.FetchDeletedMatchAsync();

            await syncMatchService.SyncDeletedMatchesAsync(delMatches);
        }
    }
}