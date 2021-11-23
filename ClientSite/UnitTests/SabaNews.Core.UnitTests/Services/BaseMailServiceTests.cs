using Microsoft.Extensions.Logging;
using Microsoft.Extensions.Options;
using NSubstitute;
using SabaNews.Core.Models;
using SabaNews.Core.Services;
using System.IO;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Core.UnitTests.Services
{
    public class BaseMailServiceTests
    {
        private IOptionsSnapshot<MailConfigs> mailConfigs;
        private ILogger<BaseMailService> logger;


        [Fact]
        public async Task SendMailAsync_EmailIsNull_ReturnFailedCommand()
        {
            //Arrange
            var service = GetBaseMailService();

            //Act
            var result = await service.SendMailAsync(null);

            //Assert
            Assert.False(result.IsSuccessful);
        }


        [Fact]
        public async Task SendMailAsync_IsValidEmail_ReturnFailedCommand()
        {
            //Arrange
            var service = GetBaseMailService();
            var email = new MailModel { ReceiverEmail = "123" };

            //Act
            var result = await service.SendMailAsync(email);

            //Assert
            Assert.False(result.IsSuccessful);
        }


        [Fact]
        public async Task SendMailAsync_ValidEmail_ReturnFailedCommand()
        {
            //Arrange
            var service = GetBaseMailService();
            var email = new MailModel { ReceiverEmail = "test@gmail.com", Subject = "Subject", Body = "body" };

            //Act
            var result = await service.SendMailAsync(email);

            //Assert
            Assert.True(result.IsSuccessful);
        }



        #region Helpers
        private BaseMailService GetBaseMailService()
        {
            mailConfigs = Substitute.For<IOptionsSnapshot<MailConfigs>>();
            logger = Substitute.For<ILogger<BaseMailService>>();

            var service = new BaseMailService(mailConfigs, logger);

            return service;
        }
        #endregion Helpers

    }
}
