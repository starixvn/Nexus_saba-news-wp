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

    public class SyncMatchByMaxMatchIdWorker : WorkerBase
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncMatchService syncMatchService;
        private readonly AppSettings appSettings;

        public SyncMatchByMaxMatchIdWorker(
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
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncAllMatchByMaxId);
        }

        public override async Task DoWorkAsync()
        {
            long maxMatchId = await syncMatchService.GetMaxMatchIdAsync();
            var param = new SyncMatchParams
            {
                SportId = (int)SportType.Soccer,
                MaxMatchId = maxMatchId,
                RowLimit = appSettings.BatchSizeSyncMatches
            };
            var matches = await fetchMatchService.FetchMatchListAsync(param);

            await syncMatchService.SyncMatchByLastUpdatedTimeAsync(matches);

            await SyncMatchDetais(matches);
        }

        private async Task SyncMatchDetais(List<MatchResponse> matches)
        {
            var matchIds = matches.Select(m => m.MatchId).ToArray();

            await SyncMatchDetailByIdsAsync(matchIds);

            var syncLineupMatchIds = matches.Where(m => m.IsLineUp).Select(i => i.MatchId).ToArray();

            await SyncLinupAsync(syncLineupMatchIds);
            await SyncIncidents(matchIds);
        }

        public async Task SyncMatchDetailByIdsAsync(long[] matchIds)
        {
            var fetchMatchDetailParam = new SyncMatchParams
            {
                MatchIds = matchIds,
                RowLimit = appSettings.BatchSizeSyncMatches
            };
            var matches = await fetchMatchService.FetchMatchDetailAsync(fetchMatchDetailParam);

            await syncMatchService.SyncMatchStatisticAsync(matches);
        }

        private async Task SyncLinupAsync(long[] matchIds)
        {
            var lineups = await fetchMatchService.FetchMatchLineupAsync(matchIds);

            await syncMatchService.SyncMatchLineupByIdsAsync(lineups);
        }

        private async Task SyncIncidents(long[] matchIds)
        {
            foreach (var matchId in matchIds)
            {
                var incidents = await fetchMatchService.FetchMatchIncidentAsync((int)SportType.Soccer, matchId);
                await syncMatchService.SyncMatchIncidentAsync(matchId, incidents);
            }
        }
    }
}