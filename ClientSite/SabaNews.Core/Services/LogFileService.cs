using Microsoft.AspNetCore.Hosting;
using Microsoft.Extensions.Configuration;
using System;
using System.IO;
namespace SabaNews.Core.Services
{
    public interface ILogFileService
    {
        void WriteLog(string spName, string runtime);
    }
    public class LogFileService : ILogFileService
    {
        private readonly IConfiguration configuration;
        private readonly IWebHostEnvironment webHostEnvironment;

        public LogFileService(IConfiguration configuration, IWebHostEnvironment webHostEnvironment)
        {
            this.configuration = configuration;
            this.webHostEnvironment = webHostEnvironment;
        }
        public void WriteLog(string spName, string runtime)
        {
            var filePath = Path.Combine(webHostEnvironment.ContentRootPath, "LogFiles/TrackingSP.txt");
            bool IsWiteLogFile = configuration.GetValue<bool>("IsWriteLogFile");
            if (IsWiteLogFile)
            {
                if (!File.Exists(filePath))
                {
                    using FileStream fs = File.Create(filePath);
                }
                using StreamWriter w = File.AppendText("LogFiles/TrackingSP.txt");
                Log(spName, runtime, w);
            }
        }

        public static void Log(string spName, string runtime, TextWriter w)
        {
            w.Write("\r\nDate time : ");
            w.WriteLine($"{Helper.DateTimeUtilize.ChangeTimeZoneToClient(DateTime.UtcNow)}");
            w.WriteLine($"SP name: {spName}");
            w.WriteLine($"Runtime: {runtime}");
            w.WriteLine("-------------------------------");
        }
    }
}
