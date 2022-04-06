namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Livescore.Services.Interfaces;
    using SabaNews.Livescore.SyncData.Services;

    internal class SyncLiveMatchThenPushSignalWorker : BaseWorker
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly IPushSignalService pushSignalService;
        private readonly ISyncMatchService syncMatchService;
        private readonly ILiveMatchService liveMatchService;
        private readonly IMatchService matchService;

        public SyncLiveMatchThenPushSignalWorker(IFetchMatchService fetchMatchService,
            IPushSignalService pushSignalService,
            ISyncMatchService syncMatchService,
            ILiveMatchService liveMatchService,
            IMatchService matchService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.fetchMatchService = fetchMatchService;
            this.pushSignalService = pushSignalService;
            this.syncMatchService = syncMatchService;
            this.liveMatchService = liveMatchService;
            this.matchService = matchService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncLiveMatchAndPushSignalR);
        }

        public override async Task ExecuteJobAsync()
        {
            var liveMatches = await fetchMatchService.FetchLiveMatchListAsync(new SyncMatchParams());

            var matchIds = liveMatches?.Select(m => m.MatchId).ToArray();

            await liveMatchService.InsertLiveMatchToTemporaryTable(matchIds);

            if (matchIds is not null && matchIds.Length > 0)
            {
                var liveMatchList = await matchService.GetLiveMatchAsync();
                await syncMatchService.SyncMatchDetailsAsync(liveMatches);

                await pushSignalService.PushMatchListAsync(liveMatchList);

                await SyncMatchLineUpAsync(matchIds);
                await SyncLiveMatchIncidents(matchIds);

                await GetMatchDetailThenPushSignalToHubAsync(matchIds);
            }
        }

        private async Task GetMatchDetailThenPushSignalToHubAsync(long[] matchIds)
        {
            foreach (var matchId in matchIds)
            {
                var statistic = matchService.GetMatchStatisticAsync(new GetMatchStatisticCriteria { MatchId = matchId });
                var incidents = matchService.GetMatchIncidentAsync(new GetMatchIncidentCriteria { MatchId = matchId });
                var lineups = matchService.GetLineupAsync(new GetMatchLineupCriteria { MatchId = matchId });

                await Task.WhenAll(statistic, incidents, lineups);

                await pushSignalService.PushMatchDetailcAsync(matchId, await statistic, await lineups, await incidents);
            }
        }

        private async Task SyncMatchLineUpAsync(long[] matchIds)
        {
            var liveMatchLineup = await fetchMatchService.FetchMatchLineupAsync(matchIds);
            await syncMatchService.SyncMatchLineupByIdsAsync(liveMatchLineup);
        }

        private async Task SyncLiveMatchIncidents(long[] matchIds)
        {
            var tasks = new List<Task<List<MatchIncident>>>();
            var incidents = new List<MatchIncident>();

            foreach (var matchId in matchIds)
            {
                tasks.Add(
                    Task.Run(() => fetchMatchService.FetchMatchIncidentAsync((int)SportType.Soccer, matchId))
                    );
            }
            var incidentListTask = (await Task.WhenAll(tasks)).ToList();

            foreach (var item in incidentListTask)
            {
                incidents.AddRange(item);
            }

            await syncMatchService.SyncMultipleMatchIncidentAsync(incidents);
        }
    }
}