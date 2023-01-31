namespace SabaNews.Livescore.Services.Tests.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Services.Criteria.LiveMatch;
    using Xunit;

    public class LiveMatchServiceTests
    {
        private readonly IBaseService baseService;
        private readonly Fixture fixture;
        private readonly LiveMatchService liveMatchService;

        public LiveMatchServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fixture = new Fixture();
            liveMatchService = new LiveMatchService(baseService);
        }

        [Fact]
        public async Task GetNotExistedMatchAsyc_WhenCalled_ReturnExpectedResult()
        {
            var matchIds = fixture.Create<IEnumerable<long>>();
            baseService.GetDataAsync<long>(Arg.Any<GetNotExistedMatchCriteria>()).Returns(matchIds);

            var actual = await liveMatchService.GetNotExistedMatchAsyc();
            Assert.Equal(matchIds, actual);
        }

        [Fact]
        public async Task GetCurrentLiveMatchAsync_WhenCalled_ReturnExpectedResult()
        {
            var matchIds = fixture.Create<IEnumerable<long>>();
            baseService.GetDataAsync<long>(Arg.Any<GetCurrentLiveMatchCriteria>()).Returns(matchIds);

            var actual = await liveMatchService.GetCurrentLiveMatchAsync();
            Assert.Equal(matchIds, actual);
        }

        [Fact]
        public async Task InsertLiveMatchToTemporaryTable_WhenNullParam_WillCallExecuteNonQuery_WithEmptyString()
        {
            await liveMatchService.InsertLiveMatchToTemporaryTable(null);
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Is<InsertLiveMatchToTemporayTableCommand>(x => string.IsNullOrEmpty(x.MatchIds)));
        }

        [Fact]
        public async Task InsertLiveMatchToTemporaryTable_WhenHasValueParam_WillCallExecuteNonQuery_WithExpectParams()
        {
            var matchIds = new long[] { 1, 2, 3 };
            await liveMatchService.InsertLiveMatchToTemporaryTable(matchIds);
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Is<InsertLiveMatchToTemporayTableCommand>(x => x.MatchIds == "1,2,3"));
        }

        [Fact]
        public async Task UpdateLiveMatchStartTimeAsync_WhenNoMatchIdOrNoStartTime_WillNotCallExecuteNonQuery()
        {
            await liveMatchService.UpdateLiveMatchStartTimeAsync(0, null, 0);
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task UpdateLiveMatchStartTimeAsync_WhenHasMatchIdAndStartTime_WillCallExecuteNonQuery()
        {
            await liveMatchService.UpdateLiveMatchStartTimeAsync(1000, DateTime.UtcNow, 0);
            await baseService.Received().ExecuteNonQueryAsync(Arg.Is<UpdateLiveMatchStartTimeCommand>(x => x.MatchId == 1000));
        }
    }
}