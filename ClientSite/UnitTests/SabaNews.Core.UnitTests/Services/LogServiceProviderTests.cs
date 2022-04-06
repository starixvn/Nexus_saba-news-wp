
namespace SabaNews.Core.UnitTests.Services
{
    using Fanex.Logging.Extensions;
    using Fanex.Logging.Extensions.Database;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.Extensions.Configuration;
    using NLog;
    using NLog.Web;
    using NSubstitute;
    using SabaNews.Core.Services;
    using System;
    using System.Threading.Tasks;
    using Xunit;

    public class LogServiceProviderTests
    {
        private Fanex.Logging.ILogger logger;
        //private Logger infoLogger = NLogBuilder.ConfigureNLog("nlog.config").GetCurrentClassLogger();
        private IWebHostEnvironment webHostEnvironment;
        private IConfiguration configuration;
        private const int LowPerformanceRuntime = 2000;

        //[Fact]
        //public async Task WriteExceptionSentryErrorAsync_CallExpectedData()
        //{
        //    //Arrange
        //    var logService = GetLogServiceProvider();
        //    var exception = Substitute.For<Exception>();
        //    var logMessage = new LogMessage(exception.Message)
        //              .AddSection("Database Info", DatabaseInfoGetters.GetDbExceptionInfo(exception))
        //              .AddSection("Server Info", CommonInfoGetters.GetServerInfo())
        //              .AddSection("Exception Info", CommonInfoGetters.GetExceptionInfo(exception))
        //              .ToString();
        //    //Act
        //    //await logService.WriteExceptionSentryErrorAsync(exception);

        //    //Assert
        //    //await logger.Received().ErrorAsync(logMessage);
        //    //infoLogger.Received().Error(logMessage);
        //}


        #region Helpers

        private LogServiceProvider GetLogServiceProvider()
        {
            logger = Substitute.For<Fanex.Logging.ILogger>();
            webHostEnvironment = Substitute.For<IWebHostEnvironment>();
            configuration = Substitute.For<IConfiguration>();
            var service = new LogServiceProvider(logger, webHostEnvironment, configuration);

            return service;
        }

        #endregion Helpers
    }
}
