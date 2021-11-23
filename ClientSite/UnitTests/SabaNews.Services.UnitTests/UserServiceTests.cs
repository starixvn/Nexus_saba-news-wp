namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Logs;
    using SabaNews.Criteria.User;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class UserServiceTests
    {
        private IBaseService baseService;
        private ILogService logService;
        private ISessionService sessionService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task UpdateUserSavePostAsync_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetUserService();
            var userID = fixture.Create<int>();
            var listPosts = fixture.Create<string>();
            var command = fixture.Create<UpdateUserSavePostCommand>();
            var expected = fixture.Create<CommandResult>();
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateUserSavePostCommand>(x => command = x)).Returns(expected);

            //Act
            var actual = await service.UpdateUserSavePostAsync(userID, listPosts);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(userID, command.UserID);
            Assert.Equal(listPosts, command.ListPosts);
        }

        [Fact]
        public async Task GetUserByIdAsync_WhenCalled_ReturnExpectedUser()
        {
            //Arrange
            var service = GetUserService();
            var userID = fixture.Create<int>();
            var criteria = fixture.Create<GetUserByIdCriteria>();
            var expected = fixture.Create<UserSession>();
            baseService.GetDataEntityAsync<UserSession>(Arg.Do<GetUserByIdCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetUserByIdAsync(userID);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(userID, criteria.UserId);
        }

        [Fact]
        public async Task UpdateUserInfoAsync_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetUserService();
            var param = fixture.Create<UserSession>();
            var command = fixture.Create<UpdateUserInfoCommand>();
            var expected = fixture.Create<CommandResult>();
            sessionService.User = param;
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateUserInfoCommand>(x => command = x)).Returns(expected);

            //Act
            var actual = await service.UpdateUserInfoAsync(param);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(param.Id, command.UserID);
            Assert.Equal(param.Name, command._name);
            Assert.Equal(param.Password, command._password);
            Assert.Equal(param.Phone, command._phone);
            Assert.Equal(param.Gender, command._gender);
            Assert.Equal(param.Photo, command._photo);
            Assert.Equal(param.IsSubscribe, command._isSubscribe);
            await logService.Received().WriteLog(Arg.Any<WriteLogCommand>());
        }

        [Fact]
        public async Task GetAllUserAsync_WhenCalled_ReturnExpectedUsers()
        {
            //Arrange
            var service = GetUserService();
            var param = fixture.Create<GetAllUserCriteria>();
            var criteria = fixture.Create<GetAllUserCriteria>();
            var expected = fixture.Create<IEnumerable<UserSession>>();
            baseService.GetDataAsync<UserSession>(Arg.Do<GetAllUserCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetAllUserAsync(param);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(param, criteria);
        }

        [Fact]
        public async Task GetUserReportAsync_WhenCalled_ReturnExpectedUsers()
        {
            //Arrange
            var service = GetUserService();
            var param = fixture.Create<GetUserReportCriteria>();
            var criteria = fixture.Create<GetUserReportCriteria>();
            var expected = fixture.Create<IEnumerable<UserSession>>();
            baseService.GetDataAsync<UserSession>(Arg.Do<GetUserReportCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetUserReportAsync(param);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(param, criteria);
        }

        [Fact]
        public async Task UnSubcriteUserAsync_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetUserService();
            var userId = fixture.Create<int>();
            var token = fixture.Create<string>();
            var command = fixture.Create<UserUnsubribeCommand>();
            var expected = fixture.Create<CommandResult>();
            baseService.ExecuteNonQueryAsync(Arg.Do<UserUnsubribeCommand>(x => command = x)).Returns(expected);

            //Act
            var actual = await service.UnSubcriteUserAsync(userId, token);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(userId, command.UserId);
            Assert.Equal(token, command.Token);
        }

        [Fact]
        public async Task DisconnectLoginTypeAsync_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetUserService();
            var userId = fixture.Create<int>();
            var loginType = fixture.Create<string>();
            var command = fixture.Create<SocialDisconnectTypeCommand>();
            var expected = fixture.Create<CommandResult>();
            baseService.ExecuteNonQueryAsync(Arg.Do<SocialDisconnectTypeCommand>(x => command = x)).Returns(expected);

            //Act
            var actual = await service.DisconnectLoginTypeAsync(userId, loginType);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(loginType, command.LoginType);
        }

        #region Helpers
        private UserService GetUserService()
        {
            baseService = Substitute.For<IBaseService>();
            logService = Substitute.For<ILogService>();
            sessionService = Substitute.For<ISessionService>();
            var service = new UserService(baseService, logService, sessionService);

            return service;
        }
        #endregion Helpers
    }
}
