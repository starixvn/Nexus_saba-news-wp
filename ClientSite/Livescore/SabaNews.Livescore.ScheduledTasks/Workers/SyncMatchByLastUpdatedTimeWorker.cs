namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Constants;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services;

    internal class SyncMatchByLastUpdatedTimeWorker : BaseWorker
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncMatchService syncMatchService;
        private readonly IConfiguration configuration;

        public SyncMatchByLastUpdatedTimeWorker(
            ISyncMatchService syncMatchService,
            IFetchMatchService fetchMatchService,
            IConfiguration configuration,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService)
                : base(logService)
        {
            this.fetchMatchService = fetchMatchService;
            this.syncMatchService = syncMatchService;
            this.configuration = configuration;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncAllMatchByUpdatedTime);
        }

        public override async Task ExecuteJobAsync()
        {
            var lastUpdatedTime = await syncMatchService.GetMatchLastUpdatedTime();
            var param = new SyncMatchParams
            {
                SportId = (int)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime,
                RowLimit = configuration.GetValue<int>("SyncMatchRowLimit") > 0 ?
                            configuration.GetValue<int>("SyncMatchRowLimit") : CommonConstants.MaxRowLimit
            };
            var matches = await fetchMatchService.FetchMatchListAsync(param);
            var matchIds = matches.Select(m => m.MatchId).ToArray();

            var tasks = new List<Task>
            {
                syncMatchService.SyncMatchByLastUpdatedTimeAsync(matches),
                syncMatchService.InsertTemporaryMatch(matchIds)
            };

            await Task.WhenAll(tasks);
        }
    }
}