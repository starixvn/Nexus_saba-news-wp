namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    internal class SyncMatchByLastUpdatedTimeWorker : BaseWorker
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncMatchService syncMatchService;
        private readonly AppSettings appSettings;

        public SyncMatchByLastUpdatedTimeWorker(
            ISyncMatchService syncMatchService,
            IFetchMatchService fetchMatchService,
            AppSettings appSettings,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService)
                : base(logService)
        {
            this.fetchMatchService = fetchMatchService;
            this.syncMatchService = syncMatchService;
            this.appSettings = appSettings;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncAllMatchByUpdatedTime);
        }

        public override async Task ExecuteJobAsync()
        {
            var lastUpdatedTime = await syncMatchService.GetMatchLastUpdatedTime();
            var param = new SyncMatchParams
            {
                SportId = (int)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime,
                RowLimit = appSettings.BatchSizeSyncMatches
            };
            var matches = await fetchMatchService.FetchMatchListAsync(param);

            if (appSettings.IsSkipSyncFinishedMatches)
            {
                ExecludeFinishedMatches(matches);
            }
            var matchIds = matches.Select(m => m.MatchId).ToArray();

            var tasks = new List<Task>
            {
                syncMatchService.SyncMatchByLastUpdatedTimeAsync(matches),
                syncMatchService.InsertTemporaryMatch(matchIds)
            };

            await Task.WhenAll(tasks);
        }

        private void ExecludeFinishedMatches(List<MatchResponse> matches)
        {
            matches.RemoveAll(m => m.GameType != (int)MatchStatus.Finished);
        }
    }
}