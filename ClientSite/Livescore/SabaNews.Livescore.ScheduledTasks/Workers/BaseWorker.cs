namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System;
    using System.Threading;
    using System.Threading.Tasks;
    using Microsoft.Extensions.Hosting;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;

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
            logService.WriteLogFileInfo($"{jobSetting.Name}: Service starting");
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
                _ = logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo($"{jobSetting.Name}: Error - {ex.Message}");
            }
            finally
            {
                isRunning = false;
            }
        }

        public virtual async Task ExecuteJobAsync()
        {
            try
            {
                await Task.CompletedTask;
            }
            catch (Exception ex)
            {
                await logService.WriteMessageSentryErrorAsync(ex.Message);
                await logService.WriteExceptionSentryErrorAsync(ex);
                throw;
            }
        }

        public override async Task StopAsync(CancellationToken cancellationToken)
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Stopping service");
            timer?.Change(Timeout.Infinite, 0);

            await base.StopAsync(cancellationToken);
        }

        public override void Dispose()
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Disposing service");
            timer.Dispose();
            GC.SuppressFinalize(this);
            base.Dispose();
        }
    }
}