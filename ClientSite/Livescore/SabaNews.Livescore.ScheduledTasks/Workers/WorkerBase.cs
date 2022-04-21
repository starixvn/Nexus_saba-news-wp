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
            await Task.Delay(10).ConfigureAwait(false);

            try
            {
                while (!stoppingToken.IsCancellationRequested)
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

                    await Task.Delay(jobSetting.Interval * 60000, stoppingToken);
                }

                logService.WriteLogFileInfo($"Execution ended. Cancelation token cancelled = {stoppingToken.IsCancellationRequested}");
            }
            catch (Exception ex) when (stoppingToken.IsCancellationRequested)
            {
                logService.WriteLogFileInfo("Execution Cancelled");
                await logService.WriteExceptionSentryErrorAsync(ex);
            }
            catch (Exception ex)
            {
                logService.WriteLogFileInfo("Unhandeled exception. Execution Stopping");
                await logService.WriteExceptionSentryErrorAsync(ex);
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