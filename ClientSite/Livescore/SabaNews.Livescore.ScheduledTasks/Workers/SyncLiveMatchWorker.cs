namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    internal class SyncLiveMatchWorker : BaseWorker
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ICacheService cacheService;
        private readonly ISyncMatchService syncMatchService;

        public SyncLiveMatchWorker(IFetchMatchService fetchMatchService,
            ICacheService cacheService,
            ISyncMatchService syncMatchService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.fetchMatchService = fetchMatchService;
            this.cacheService = cacheService;
            this.syncMatchService = syncMatchService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncLiveMatch);
        }

        public override async Task ExecuteJobAsync()
        {
            var liveMatches = await fetchMatchService.FetchLiveMatchListAsync(new SyncMatchParams());
            if (liveMatches.Any())
            {
                var matchIds = liveMatches.Select(m => m.MatchId).ToArray();
                await InsertTemporaryLiveMatch(matchIds);
                var liveMatchLineup = await SyncMatchLineUpAsync(matchIds);
                var liveMatchIncidents = await SyncLiveMatchIncidents(matchIds);
            }

            //Convert to domain models
            //Save to cache
            //Push to Hub SignalR
        }

        private Task InsertTemporaryLiveMatch(long[] matchIds)
        {
            throw new System.NotImplementedException();
        }

        private async Task<List<MatchLineupResponse>> SyncMatchLineUpAsync(long[] matchIds)
        {
            var liveMatchLineup = await fetchMatchService.FetchMatchLineupAsync(matchIds);
            await syncMatchService.SyncMatchLineupByIdsAsync(liveMatchLineup);
            return liveMatchLineup;
        }

        private async Task<List<MatchIncident>> SyncLiveMatchIncidents(long[] matchIds)
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
            return incidents;
        }
    }
}