namespace SabaNews.Core.Services
{
    using System;
    using System.Threading.Tasks;

    public interface ILogServiceProvider
    {
        Task WriteExceptionSentryErrorAsync(Exception exception);

        Task WriteMessageSentryErrorAsync(string logMessage);

        void WriteLogFileInfo(string logMessage);
    }
}
