namespace SabaNews.Livescore.ScheduledTasks.Workers
{
    using System;
    using System.Threading;
    using System.Threading.Tasks;
    using Microsoft.Extensions.Hosting;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;

    public abstract class WorkerBase : BackgroundService
    {
        private readonly ILogServiceProvider logService;
        protected ScheduledTaskSettings jobSetting;

        protected WorkerBase(ILogServiceProvider logService)
        {
            this.logService = logService;
        }

        public override Task StartAsync(CancellationToken cancellationToken)
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Service starting");
            return base.StartAsync(cancellationToken);
        }

        public abstract Task DoWorkAsync();

        protected override async Task ExecuteAsync(CancellationToken stoppingToken)
        {
            const byte MillisecondsDelay = 10;
            await Task.Delay(MillisecondsDelay).ConfigureAwait(false);

            try
            {
                while (!stoppingToken.IsCancellationRequested)
                {
                    await RunJobAsync();
                    await Task.Delay(TimeSpan.FromMinutes(jobSetting.Interval), stoppingToken);
                }

                logService.WriteLogFileInfo($"Execution ended. Cancelation token cancelled = {stoppingToken.IsCancellationRequested}");
            }
            catch (Exception ex)
            {
                logService.WriteLogFileInfo("Unhandeled exception. Execution Stopping");
                logService.WriteLogFileInfo(ex.Message);
            }
        }

        private async Task RunJobAsync()
        {
            try
            {
                if (jobSetting.Enabled)
                {
                    logService.WriteLogFileInfo($"{jobSetting.Name}: Doing...");
                    await DoWorkAsync();
                    logService.WriteLogFileInfo($"{jobSetting.Name}: Done.");
                }
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
                logService.WriteLogFileInfo($"{jobSetting.Name}: Error - {ex.Message}");
            }
        }

        public override async Task StopAsync(CancellationToken cancellationToken)
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Stopping service");

            await base.StopAsync(cancellationToken);
        }

        public override void Dispose()
        {
            logService.WriteLogFileInfo($"{jobSetting.Name}: Disposing service");
            GC.SuppressFinalize(this);
            base.Dispose();
        }
    }
}