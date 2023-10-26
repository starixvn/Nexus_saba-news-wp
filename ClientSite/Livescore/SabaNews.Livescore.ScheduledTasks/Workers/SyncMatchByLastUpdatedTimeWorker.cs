namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncMatchByLastUpdatedTimeWorker : WorkerBase
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

        public override async Task DoWorkAsync()
        {
            var lastUpdatedTime = await syncMatchService.GetMatchLastUpdatedTimeAsync();
            var param = new SyncMatchParams
            {
                SportId = (int)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime,
                RowLimit = appSettings.BatchSizeSyncMatches
            };
            var matches = await fetchMatchService.FetchMatchListAsync(param);

            if (matches is not null && matches.Any())
            {
                var maxUpdatedTime = matches.Select(x => x.UpdatedAt).Max();

                if (lastUpdatedTime.ToString(CommonConstants.OutgoingDatetimeFormat) == maxUpdatedTime.ToString(CommonConstants.OutgoingDatetimeFormat))
                {
                    await SyncMatchInPastAsync();
                    await SyncMatchFutureAsync();
                }
                else
                {
                    await syncMatchService.SyncMatchByLastUpdatedTimeAsync(matches);

                    await SyncMatchDetais(matches);
                }
            }
        }

        private async Task SyncMatchFutureAsync()
        {
            var startTime = DateTimeUtilize.UtcNow().Date;
            var endTime = startTime.AddDays(appSettings.NumOfDayFutureToSyncMatch + 1).AddSeconds(-1);

            var lastUpdatedTime = await syncMatchService.GetMatchLastUpdatedTimeInDateRangeAsync(startTime, endTime);

            await syncMatchService.SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(startTime, endTime, lastUpdatedTime);
        }

        private async Task SyncMatchInPastAsync()
        {
            var endTime = DateTimeUtilize.UtcNow().Date.AddSeconds(-1);
            var startTime = endTime.AddDays(appSettings.NumOfDayPastToSyncMatch);

            var lastUpdatedTime = await syncMatchService.GetMatchLastUpdatedTimeInDateRangeAsync(startTime, endTime, isExcludeLiveMatch: true);
            await syncMatchService.SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(startTime, endTime, lastUpdatedTime);
        }

        private async Task SyncMatchDetais(List<MatchResponse> matches)
        {
            var matchIds = matches.Select(m => m.MatchId).ToArray();

            await SyncMatchDetailByIdsAsync(matchIds);

            var syncLineupMatchIds = matches.Where(m => m.IsLineUp).Select(i => i.MatchId).ToArray();

            await SyncLinupAsync(syncLineupMatchIds);
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
            if (matchIds is not null && matchIds.Any())
            {
                var lineups = await fetchMatchService.FetchMatchLineupAsync(matchIds);

                await syncMatchService.SyncMatchLineupByIdsAsync(lineups);
            }
        }
    }
}