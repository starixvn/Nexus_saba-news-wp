namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Constants;
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
        private IFetchMatchService fetchMatchService;
        private ICacheService cacheService;
        private ISyncMatchService syncMatchService;

        public SyncLiveMatchWorker(IFetchMatchService fetchMatchService,
            ICacheService cacheService,
            ISyncMatchService syncMatchService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.fetchMatchService = fetchMatchService;
            this.cacheService = cacheService;
            this.syncMatchService = syncMatchService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncLeagueListGreaterThanLeagueId);
        }

        public override async Task ExecuteJobAsync()
        {
            var liveMatches = await fetchMatchService.FetchLiveMatchListAsync(new SyncMatchParams());
            ExecludeNotStartMatches(liveMatches);

            if (liveMatches.Any())
            {
                var previousLiveMatches = await GetPreviousLiveMatchIds();
            }
        }

        private async Task<List<MatchDetailResponse>> GetPreviousLiveMatchIds()
        {
            var preLiveMatches = await cacheService.GetAsync<List<MatchDetailResponse>>(CachingKeys.LiveMatchProgressKey);

            if (preLiveMatches is null)
            {
            }

            return preLiveMatches;
        }

        private void ExecludeNotStartMatches(List<MatchDetailResponse> liveMatches)
        {
            liveMatches.RemoveAll(m => m.GameType is null || m.GameType == (int)MatchStatus.NotStarted);
        }
    }
}