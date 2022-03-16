namespace SabaNews.WinowsService.Workers
{
    using System;
    using System.Threading;
    using System.Threading.Tasks;
    using Microsoft.Extensions.Hosting;
    using SabaNews.AutoJobs.Models;
    using SabaNews.Core.Services;

    public class BaseWorker : BackgroundService
    {
        private readonly ILogServiceProvider logService;
        private Timer timer;
        private bool isRunning;
        protected ScheduledTaskSettings jobSetting;

        public BaseWorker(ILogServiceProvider logService)
        {
            this.logService = logService;
        }

        protected override async Task ExecuteAsync(CancellationToken stoppingToken)
        {
            timer = new Timer(Execute,
               null,
               TimeSpan.Zero,
               TimeSpan.FromMinutes(jobSetting.Interval));

            await Task.CompletedTask;
        }

        public override Task StartAsync(CancellationToken cancellationToken)
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Service Starting");
            return base.StartAsync(cancellationToken);
        }

        private void Execute(object state)
        {
            try
            {
                if (!isRunning && jobSetting.Enabled)
                {
                    isRunning = true;
                    logService.WriteLogFileInfo($"{jobSetting.Name}: Doing...");
                    _ = ExecuteJobAsync();
                    logService.WriteLogFileInfo($"{jobSetting.Name}: Done.");
                }
            }
            catch (Exception ex)
            {
                logService.WriteLogFileInfo($"{jobSetting.Name}: Error - {ex}");
            }
            finally
            {
                isRunning = false;
            }
        }

        public virtual async Task ExecuteJobAsync()
        {
            await Task.CompletedTask;
        }

        public override async Task StopAsync(CancellationToken cancellationToken)
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Stopping Service");
            timer?.Change(Timeout.Infinite, 0);

            await base.StopAsync(cancellationToken);
        }

        public override void Dispose()
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Disposing Service");
            timer.Dispose();
            GC.SuppressFinalize(this);
            base.Dispose();
        }
    }
}