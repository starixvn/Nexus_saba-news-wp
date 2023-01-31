namespace SabaNews.Livescore.SyncData.Tests.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.SyncData.Criteria.HeadToHead;
    using SabaNews.Livescore.SyncData.Models.HeadToHead;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncHeadToHeadServiceTests
    {
        private readonly IBaseService baseService;
        private readonly Fixture fixture;
        private readonly SyncHeadToHeadService syncHeadToHeadService;

        public SyncHeadToHeadServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fixture = new Fixture();
            syncHeadToHeadService = new SyncHeadToHeadService(baseService);
        }

        [Fact]
        public async Task GetMatchToSyncHeadToHeadByDateRange_WhenCalled_ReturnExpectedResult()
        {
            var matches = fixture.Create<IEnumerable<MatchToGetH2H>>();
            var from = new DateTime(2022, 1, 1);

            baseService.GetDataAsync<MatchToGetH2H>(Arg.Any<GetMatchToSyncH2HCriteria>()).Returns(matches);
            var actual = await syncHeadToHeadService.GetMatchToSyncHeadToHeadByDateRange(from, from.AddDays(10), 10);
            Assert.Equal(matches, actual);
        }

        [Fact]
        public async Task SyncHeadToHeadAsync_WhenNullInput_WillNotCallBaseService()
        {
            await syncHeadToHeadService.SyncHeadToHeadAsync(null);
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncHeadToHeadAsync_WhenHadValueInput_WillCallBaseService()
        {
            HeadToHeadResponse h2h = fixture.Create<HeadToHeadResponse>();
            await syncHeadToHeadService.SyncHeadToHeadAsync(h2h);
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task UpdateSyncCompletedHeadToHead_WhenCalled_WillCallBaseService()
        {
            await syncHeadToHeadService.UpdateSyncCompletedHeadToHead(1000);
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }
    }
}