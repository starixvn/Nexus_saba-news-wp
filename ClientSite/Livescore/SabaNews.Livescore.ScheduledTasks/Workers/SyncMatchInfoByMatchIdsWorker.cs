namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    internal class SyncMatchInfoByMatchIdsWorker : BaseWorker
    {
        private readonly ISyncMatchService syncMatchService;
        private readonly IFetchMatchService fetchMatchService;

        public SyncMatchInfoByMatchIdsWorker(
            ISyncMatchService syncMatchService,
            IFetchMatchService fetchMatchService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService)
                : base(logService)
        {
            this.syncMatchService = syncMatchService;
            this.fetchMatchService = fetchMatchService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncMatchInfoByMatchIds);
        }

        public override async Task ExecuteJobAsync()
        {
            try
            {
                var temporayMatches = await syncMatchService.GetTemporaryMatchToFetchInfo(CommonConstants.MaxMatchDetailRowLimit);

                if (temporayMatches.Any())
                {
                    var matchIds = temporayMatches.Select(m => m.MatchId).ToArray();

                    var syncLiveTextMatchIds = temporayMatches.Where(i => i.IsStarted).Select(m => m.MatchId).ToArray();
                    var syncLineupMatchIds = temporayMatches.Where(m => m.IsLineUp).Select(i => i.MatchId).ToArray();

                    await syncMatchService.SyncMatchDetailByIdsAsync(matchIds);
                    await syncMatchService.SyncMatchLineupByIdsAsync(syncLineupMatchIds);

                    await SyncCommentary(syncLiveTextMatchIds);
                    await SyncIncidents(syncLiveTextMatchIds);

                    await syncMatchService.RemoveMatchesFromTemporaryTable(matchIds);
                }
            }
            catch (System.Exception ex)
            {
                throw;
            }
        }

        private async Task SyncCommentary(long[] matchIds)
        {
            var tasks = new List<Task<List<MatchCommentary>>>();
            var commentaries = new List<MatchCommentary>();

            foreach (var matchId in matchIds)
            {
                tasks.Add(
                    Task.Run(() => fetchMatchService.FetchMatchCommentaryAsync(matchId))
                    );
            }
            var commentaryListTask = (await Task.WhenAll(tasks)).ToList();

            foreach (var item in commentaryListTask)
            {
                commentaries.AddRange(item);
            }

            await syncMatchService.SyncMultipleMatchCommentaryAsync(commentaries);
        }

        private async Task SyncIncidents(long[] matchIds)
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