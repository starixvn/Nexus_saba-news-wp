namespace SabaNews.Core.Services
{
    using System;
    using System.IO;
    using System.Threading.Tasks;
    using Fanex.Logging.Extensions;
    using Fanex.Logging.Extensions.Database;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.Extensions.Configuration;
    using NLog;
    using NLog.Web;

    public class LogServiceProvider : ILogServiceProvider
    {
        private readonly Fanex.Logging.ILogger logger;
        private readonly Logger infoLogger = NLogBuilder.ConfigureNLog("nlog.config").GetCurrentClassLogger();
        private readonly IWebHostEnvironment webHostEnvironment;
        private readonly IConfiguration configuration;

        public LogServiceProvider(
            Fanex.Logging.ILogger logger, 
            IWebHostEnvironment webHostEnvironment,
            IConfiguration configuration)
        {
            this.logger = logger;
            this.webHostEnvironment = webHostEnvironment;
            this.configuration = configuration;
        }

        public async Task WriteExceptionSentryErrorAsync(Exception exception)
        {
            var logMessage = new LogMessage(exception.Message)
                       .AddSection("Database Info", DatabaseInfoGetters.GetDbExceptionInfo(exception))
                       .AddSection("Server Info", CommonInfoGetters.GetServerInfo())
                       .AddSection("Exception Info", CommonInfoGetters.GetExceptionInfo(exception))
                       .ToString();
            await logger.ErrorAsync(logMessage);
            infoLogger.Error(logMessage);
        }

        public async Task WriteMessageSentryErrorAsync(string logMessage)
        {
            await logger.ErrorAsync(logMessage);
        }

        public void WriteLogFileInfo(string logMessage)
        {
            infoLogger.Error(logMessage);
        }


        public void WriteLogFileTrackingSP (string spName, string runtime)
        {
            try
            {
                var filePath = Path.Combine(webHostEnvironment.ContentRootPath, "LogFiles/TrackingSP.txt");
                bool IsWiteLogFile = configuration.GetValue<bool>("IsWriteLogFile");
                if (IsWiteLogFile)
                {
                    if (!File.Exists(filePath))
                    {
                        File.Create(filePath).Dispose();
                    }
                    using StreamWriter w = File.AppendText("LogFiles/TrackingSP.txt");
                    Log(spName, runtime, w);
                }
            }
            catch (Exception e)
            {
                _ = e.Message;
            }

        }

        public static void Log(string spName, string runtime, TextWriter w)
        {
            w.Write("\r\nDate time: ");
            w.Write($"{Helper.DateTimeUtilize.ChangeTimeZoneToClient(DateTime.UtcNow)}");
            w.Write($" --- SP name: {spName} --- Runtime: {runtime}");
            w.Write("\r\n-------------------------------");
        }
    }
}
