namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Helpers;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.SyncData.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class SyncCountryListWorker : WorkerBase
    {
        private readonly ISyncCountryService syncCountryService;

        public SyncCountryListWorker(
            ISyncCountryService syncCountryService,
            List<ScheduledTaskSettings> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.syncCountryService = syncCountryService;
            jobSetting = ScheduledTaskHelper.GetJobConfig(jobSettings, JobName.SyncCountryList);
        }

        public override async Task DoWorkAsync()
        {
            await syncCountryService.SyncCountryListAsync();
        }
    }
}
