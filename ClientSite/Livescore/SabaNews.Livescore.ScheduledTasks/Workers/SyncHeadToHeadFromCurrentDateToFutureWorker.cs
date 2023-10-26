namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Models.HeadToHead;
    using SabaNews.Livescore.SyncData.Services.Interfaces;

    public class SyncHeadToHeadFromCurrentDateToFutureWorker : WorkerBase
    {
        private readonly ISyncHeadToHeadService synch2hService;
        private readonly IFetchTeamService fetchTeamService;
        private readonly AppSettings appSettings;

        public SyncHeadToHeadFromCurrentDateToFutureWorker(
            ISyncHeadToHeadService synch2hService,
            IFetchTeamService fetchTeamService,
            List<ScheduledTaskSettings> jobSettings,
            AppSettings appSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.synch2hService = synch2hService;
            this.fetchTeamService = fetchTeamService;
            this.appSettings = appSettings;

            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncHeadToHeadFromCurrentToFuture);
        }

        public override async Task DoWorkAsync()
        {
            var h2hMatches = await GetMatchToSyncHeadToHead();
            if (h2hMatches.Any())
            {
                foreach (var m in h2hMatches)
                {
                    var h2h = await fetchTeamService.FetchHeadToHeadAsync(m.MatchId, m.HomeTeamId, m.AwayTeamId);
                    await synch2hService.SyncHeadToHeadAsync(h2h);
                    await synch2hService.UpdateSyncCompletedHeadToHead(m.MatchId);
                }
            }
        }

        private async Task<IEnumerable<MatchToGetH2H>> GetMatchToSyncHeadToHead()
        {
            var fromDate = DateTimeUtilize.UtcNow().AddDays(-1).Date;
            var toDate = fromDate.AddDays(appSettings.HeadToHeadMaxFutureDays + 1).AddSeconds(-1);

            var matches = await synch2hService.GetMatchToSyncHeadToHeadByDateRange(fromDate, toDate, appSettings.SyncHeadToHeadRowLimit);
            return matches;
        }
    }
}