namespace SabaNews.Core.UnitTests.Services
{
    using MailKit.Net.Smtp;
    using Microsoft.Extensions.Logging;
    using Microsoft.Extensions.Options;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using System.Threading.Tasks;
    using Xunit;

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


        [Theory]
        [InlineData("123")]
        [InlineData("")]
        public async Task SendMailAsync_IsValidEmail_ReturnFailedCommand(string emailAddress)
        {
            //Arrange
            var service = GetBaseMailService();
            
            var email = new MailModel { ReceiverEmail = emailAddress };
            
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
            var smtp = Substitute.For<ISmtpClient>();
            await smtp.ConnectAsync(Arg.Do<string>(x => x = "string"), Arg.Do<int>(x => x = 1), Arg.Do<bool>(x => x = true));
            //Act
            var result = await service.SendMailAsync(email);

            //Assert
            Assert.False(result.IsSuccessful);
        }



        #region Helpers
        private BaseMailService GetBaseMailService()
        {
            var mailConfig = Substitute.For<IOptionsSnapshot<MailConfigs>>();
            var config = new MailConfigs
            {
                Enabled = true,
                SenderName = "sender name",
                SmtpServer = "smtp server",
                SmtpPort = 80,
                EnableSsl = true,
                MailPriority = 0,
                Password = "123456",
                Username = "username"
            };

            mailConfig.Value.Returns(config);
            mailConfigs = Substitute.For<IOptionsSnapshot<MailConfigs>>();
            logger = Substitute.For<ILogger<BaseMailService>>();

            var service = new BaseMailService(mailConfig, logger);

            return service;
        }
        #endregion Helpers

    }
}
