namespace SabaNews.WindowsService.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.Extensions.Options;
    using SabaNews.Core.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.WindowsService.Constants;
    using SabaNews.WindowsService.Helpers;
    using SabaNews.WindowsService.Models;
    using SabaNews.WinowsService.Workers;

    public class RefreshMenuCachingWorker : BaseWorker
    {
        private readonly IRefreshCachingService refreshCachingService;

        public RefreshMenuCachingWorker(IRefreshCachingService refreshCachingService,
            IOptions<List<JobSettings>> jobSettings,
            ILogServiceProvider logService) : base(logService)
        {
            this.refreshCachingService = refreshCachingService;
            jobSetting = JobSettingHelper.GetJobConfig(jobSettings.Value, JobNames.RefreshMenuCaching);
        }

        public override async Task ExecuteJobAsync()
        {
            await refreshCachingService.UpdateMenuCaching();
        }
    }
}
