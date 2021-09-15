using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Core.Services
{
    public interface ILogFileService
    {
        void WriteLog(string spName, string runtime);
    }
    public class LogFileService : ILogFileService
    {
        private readonly IConfiguration configuration;
        public LogFileService(IConfiguration configuration)
        {
            this.configuration = configuration;
        }
        public void WriteLog(string spName, string runtime)
        {
            bool IsWiteLogFile = configuration.GetValue<bool>("IsWriteLogFile");
            if (IsWiteLogFile)
            {
                using StreamWriter w = File.AppendText("LogFiles/LogTrackingSP.txt");
                Log(spName, runtime, w);
            }
        }

        public static void Log(string spName, string runtime, TextWriter w)
        {
            w.Write("\r\nLog Entry : ");
            w.WriteLine($"{DateTime.Now.ToLongTimeString()} {DateTime.Now.ToLongDateString()}");
            w.WriteLine($"SP name: {spName}");
            w.WriteLine($"Time: {runtime}");
            w.WriteLine("-------------------------------");
        }
    }
}
