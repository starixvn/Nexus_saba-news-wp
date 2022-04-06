namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.Poll;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;

    public class PollServiceTests
    {
        private IBaseService baseService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task GetPollAsync_WhenCalled_ReturnExpectedPollViewModel()
        {
            //Arrange
            var service = GetPollService();
            var param = fixture.Create<GetPollCriteria>();
            
            var polls = fixture.Create<IEnumerable<PollModel>>();
            var questions = fixture.Create<IEnumerable<QuestionModel>>();
            var expected = new ValueTuple<IEnumerable<PollModel>, IEnumerable<QuestionModel>>(polls, questions);
            baseService.FetchMultipleAsync<PollModel, QuestionModel>(Arg.Do<GetPollCriteria>(x => param = x)).Returns(expected);

            //Act
            var actual = await service.GetPollAsync(param);

            //Assert
            Assert.Equal(expected.Item1, actual.Polls);
            Assert.Equal(expected.Item2.FirstOrDefault(), actual.Question);
        }

        [Fact]
        public async Task VotePollAsync_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetPollService();
            var questionId = fixture.Create<int>();
            var answerId = fixture.Create<int>();
            var userIp = fixture.Create<string>();
            var command = fixture.Create<VotePollSurveyCommand>();
            var expected = fixture.Create<CommandResult>();
            baseService.ExecuteNonQueryAsync(Arg.Do<VotePollSurveyCommand>(x => command = x)).Returns(expected);

            //Act
            var actual = await service.VotePollAsync(questionId, answerId, userIp);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(questionId, command.QuestId);
            Assert.Equal(answerId, command.AnswerId);
            Assert.Equal(userIp, command.UserIP);
        }

        #region Helpers
        private PollService GetPollService()
        {
            baseService = Substitute.For<IBaseService>();
            var service = new PollService(baseService);

            return service;
        }
        #endregion Helpers
    }
}
