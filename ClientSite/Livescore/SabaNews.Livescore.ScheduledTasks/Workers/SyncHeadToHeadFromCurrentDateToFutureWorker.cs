namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    internal class SyncHeadToHeadFromCurrentDateToFutureWorker : BaseWorker
    {
        private readonly IFetchTeamService fetchTeamService;
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncTeamService syncTeamService;
        private readonly AppSettings appSettings;

        public SyncHeadToHeadFromCurrentDateToFutureWorker(IFetchTeamService fetchTeamService,
            IFetchMatchService fetchMatchService,
            ISyncTeamService syncTeamService,
            List<ScheduledTaskSettings> jobSettings,
            AppSettings appSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.fetchTeamService = fetchTeamService;
            this.fetchMatchService = fetchMatchService;
            this.syncTeamService = syncTeamService;
            this.appSettings = appSettings;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncHeadToHeadFromCurrentToFuture);
        }

        public override async Task ExecuteJobAsync()
        {
            var matches = await GetCurrentToFutureMatchesAsync();

            var tasks = new List<Task<HeadToHeadResponse>>();

            foreach (var m in matches)
            {
                tasks.Add(fetchTeamService.FetchHeadToHeadAsync(m.HId, m.AId));
            }
            var h2hList = (await Task.WhenAll(tasks)).ToList();

            h2hList.RemoveAll(i => i is null);
            await syncTeamService.SyncHeadToHeadAsync(h2hList);
        }

        private async Task<List<MatchResponse>> GetCurrentToFutureMatchesAsync()
        {
            var from = DateTime.UtcNow.Date;
            var to = from.AddDays(appSettings.HeadToHeadMaxFutureDays).Date.AddDays(1).AddSeconds(-1);
            var matches = await fetchMatchService.FetchMatchListAsync(
                new SyncMatchParams
                {
                    StartTime = from,
                    EndTime = to,
                    RowLimit = 100
                });
            return matches;
        }
    }
}