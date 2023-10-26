namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Constants.Livescore;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Livescore.Services.Interfaces;
    using SabaNews.Livescore.SyncData.Services;

    public class SyncLiveMatchThenPushSignalWorker : WorkerBase
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly IPushSignalService pushSignalService;
        private readonly ISyncMatchService syncMatchService;
        private readonly ILiveMatchService liveMatchService;
        private readonly IMatchService matchService;
        private readonly AppSettings appSettings;

        public SyncLiveMatchThenPushSignalWorker(IFetchMatchService fetchMatchService,
            IPushSignalService pushSignalService,
            ISyncMatchService syncMatchService,
            ILiveMatchService liveMatchService,
            IMatchService matchService,
            List<ScheduledTaskSettings> jobSettings,
            AppSettings appSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.fetchMatchService = fetchMatchService;
            this.pushSignalService = pushSignalService;
            this.syncMatchService = syncMatchService;
            this.liveMatchService = liveMatchService;
            this.matchService = matchService;
            this.appSettings = appSettings;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncLiveMatchAndPushSignalR);
        }

        public override async Task DoWorkAsync()
        {
            var liveMatches = await fetchMatchService.FetchLiveMatchListAsync(new SyncMatchParams());
            var matchIds = liveMatches?.Select(m => m.MatchId).ToArray();
            var prevLiveMatches = await liveMatchService.GetCurrentLiveMatchAsync();

            await liveMatchService.InsertLiveMatchToTemporaryTable(matchIds);

            var removedFromLiveMatches = prevLiveMatches.Where(id => matchIds.ToList().TrueForAll(i => i != id));
            var notExistedMatches = await liveMatchService.GetNotExistedMatchAsyc();

            await SyncMatchInfoAdditionalAsync(removedFromLiveMatches.Concat(notExistedMatches));

            if (matchIds is not null && matchIds.Length > 0)
            {
                await syncMatchService.SyncMatchStatisticAsync(liveMatches);

                var liveMatchList = await matchService.GetLiveMatchAsync();

                await pushSignalService.PushMatchListAsync(liveMatchList);

                var liveMatchIds = liveMatchList?.Where(val => val.Status != MatchConstants.NotStarted).Select(m => m.MatchId).ToArray();
                await SyncMatchLineUpAsync(matchIds);
                if(appSettings.IsSyncIncident)
                {
                    await syncMatchService.SyncIncidents(liveMatchIds);
                }

                await GetMatchDetailThenPushSignalToHubAsync(matchIds);
            }
        }

        private async Task SyncMatchInfoAdditionalAsync(IEnumerable<long> matchIds)
        {
            if (matchIds.Any())
            {
                var param = new SyncMatchParams
                {
                    SportId = (int)SportType.Soccer,
                    MatchIds = matchIds.ToArray(),
                    RowLimit = appSettings.BatchSizeSyncMatches
                };

                var matches = await fetchMatchService.FetchMatchListAsync(param);

                if (matches is not null && matches.Any())
                {
                    await syncMatchService.SyncMatchByLastUpdatedTimeAsync(matches, skipUpdatedAt: true);
                }
            }
        }

        private async Task GetMatchDetailThenPushSignalToHubAsync(long[] matchIds)
        {
            foreach (var matchId in matchIds)
            {
                var detail = new MatchDetail
                {
                    GeneralInfo = await matchService.GetMatchByIdAsync(new GetMatchByIdCriteria { MatchId = matchId }),
                    Incidents = await matchService.GetMatchIncidentAsync(new GetMatchIncidentCriteria { MatchId = matchId }),
                    Statistic = await matchService.GetMatchStatisticAsync(new GetMatchStatisticCriteria { MatchId = matchId }),
                    HeadToHead = await matchService.GetHeadToHeadAsync(new GetHeadToHeadCriteria { MatchId = matchId }),
                    Lineup = await matchService.GetLineupAsync(new GetMatchLineupCriteria { MatchId = matchId })
                };

                await pushSignalService.PushMatchDetailcAsync(matchId, detail);
            }
        }

        private async Task SyncMatchLineUpAsync(long[] matchIds)
        {
            if (matchIds.Any())
            {
                var liveMatchLineup = await fetchMatchService.FetchMatchLineupAsync(matchIds);

                await syncMatchService.SyncMatchLineupByIdsAsync(liveMatchLineup);
            }
        }
    }
}