namespace SabaNews.Livescore.SyncData.Tests.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncMatchServiceTests
    {
        private readonly IBaseService baseService;
        private readonly IFetchMatchService fetch;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly SyncMatchService sync;

        public SyncMatchServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fetch = Substitute.For<IFetchMatchService>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            sync = new SyncMatchService(baseService, fetch, appSettings);
        }

        [Fact]
        public async Task SyncMatchByLastUpdatedTimeAsync_WhenNoMatchResponse_DoNothing()
        {
            await sync.SyncMatchByLastUpdatedTimeAsync(new List<MatchResponse>());
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMatchByLastUpdatedTimeAsync_WhenHasMatchResponse_CallExecuteNonQuery()
        {
            await sync.SyncMatchByLastUpdatedTimeAsync(fixture.Create<List<MatchResponse>>());
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMatchStatisticAsync_WhenNoMatchResponse_DoNothing()
        {
            await sync.SyncMatchStatisticAsync(new List<MatchDetailResponse>());
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMatchStatisticAsync_WhenHasMatchResponse_CallExecuteNonQuery()
        {
            var data = fixture.Create<List<MatchDetailResponse>>();
            foreach (var item in data)
            {
                item.ExtraData.ControlRate = null;
            }
            await sync.SyncMatchStatisticAsync(data);
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMatchLineupByIdsAsync_WhenNoMatchResponse_DoNothing()
        {
            await sync.SyncMatchLineupByIdsAsync(new List<MatchLineupResponse>());
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMatchLineupByIdsAsync_WhenHasMatchResponse_CallExecuteNonQuery()
        {
            await sync.SyncMatchLineupByIdsAsync(fixture.Create<List<MatchLineupResponse>>());
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMultipleMatchCommentaryAsync_WhenNoMatchResponse_DoNothing()
        {
            await sync.SyncMultipleMatchCommentaryAsync(new List<MatchCommentary>());
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMultipleMatchCommentaryAsync_WhenHasMatchResponse_CallExecuteNonQuery()
        {
            await sync.SyncMultipleMatchCommentaryAsync(fixture.Create<List<MatchCommentary>>());
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMultipleMatchIncidentAsync_WhenNoMatchResponse_DoNothing()
        {
            await sync.SyncMatchIncidentAsync(1, new List<MatchIncident>());
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncMultipleMatchIncidentAsync_WhenHasMatchResponse_CallExecuteNonQuery()
        {
            await sync.SyncMatchIncidentAsync(100, fixture.Create<List<MatchIncident>>());
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task GetMatchLastUpdatedTimeAsync_WhenCalled_ReturnExpectedResult()
        {
            var expected = fixture.Create<DateTime>();
            baseService.GetDataEntityAsync<DateTime>(Arg.Any<GetMatchLastUpdatedTimeCriteria>()).Returns(expected);

            var actual = await sync.GetMatchLastUpdatedTimeAsync();
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetMaxMatchIdAsync_WhenCalled_ReturnExpectedResult()
        {
            var expected = fixture.Create<long>();
            baseService.GetDataEntityAsync<long>(Arg.Any<GatMatchMaxIdCriteria>()).Returns(expected);

            var actual = await sync.GetMaxMatchIdAsync();
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task SyncDeletedMatchesAsync_WhenHasMatchResponse_CallExecuteNonQuery()
        {
            await sync.SyncDeletedMatchesAsync(fixture.Create<List<DeletedMatch>>());
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncDeletedMatchesAsync_WhenNoMatchResponse_DoNothing()
        {
            await sync.SyncDeletedMatchesAsync(new List<DeletedMatch>());
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task GetMatchLastUpdatedTimeInDateRangeAsync_WhenCalled_ReturnExpectedResult()
        {
            var expected = fixture.Create<DateTime>();
            baseService.GetDataEntityAsync<DateTime>(Arg.Any<GetLastUpdatedTimeInDateRangeCriteria>()).Returns(expected);

            var actual = await sync.GetMatchLastUpdatedTimeInDateRangeAsync(null, null, true);
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync_WhenNoMatch_WillNotCallAnotherAPI()
        {
            await sync.SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(null, null, null);
            await fetch.Received(1).FetchMatchListAsync(Arg.Any<SyncMatchParams>());
            await fetch.DidNotReceive().FetchMatchDetailAsync(Arg.Any<SyncMatchParams>());
            await fetch.DidNotReceive().FetchMatchLineupAsync(Arg.Any<long[]>());
            await fetch.DidNotReceive().FetchMatchIncidentAsync(1, Arg.Any<long>());
        }

        [Fact]
        public async Task SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync_WhenHasMatch_WillCallAnotherAPI()
        {
            var matches = fixture.Create<List<MatchResponse>>();
            matches.First().IsLineUp = true;
            fetch.FetchMatchListAsync(Arg.Any<SyncMatchParams>()).Returns(matches);

            var details = fixture.Create<List<MatchDetailResponse>>();
            foreach (var item in details)
            {
                item.ExtraData.ControlRate = null;
            }
            fetch.FetchMatchDetailAsync(Arg.Any<SyncMatchParams>()).Returns(details);

            await sync.SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(null, null, null);

            await fetch.Received(1).FetchMatchListAsync(Arg.Any<SyncMatchParams>());
            await fetch.Received(1).FetchMatchDetailAsync(Arg.Any<SyncMatchParams>());
            var isLineUpMatches = matches.Where(x => x.IsLineUp).ToList();
            await fetch.Received().FetchMatchLineupAsync(Arg.Is<long[]>(x => x.Length == matches.Count(m => m.IsLineUp)));
        }

        [Fact]
        public async Task SyncIncidentByUpdatedTimeAsync_WhenLastUpdatedTimeIsNull_ReturnExpectedResult()
        {
            DateTime? expected = null;
            var expectedMatches = fixture.Create<List<long>>();
            baseService.GetDataEntityAsync<DateTime?>(Arg.Any<GetLastUpdatedTimeInDateRangeCriteria>()).Returns(expected);
            fetch.FetchMatchIncidentUpdateTimeAsync(Arg.Any<short>(), Arg.Any<DateTime?>()).Returns(expectedMatches);

            await sync.SyncIncidentByUpdatedTimeAsync();
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncIncidentByUpdatedTimeAsync_WhenCalled_ReturnExpectedResult()
        {
            var expected = fixture.Create<DateTime>();
            var expectedMatches = fixture.Create<List<long>>();
            baseService.GetDataEntityAsync<DateTime>(Arg.Any<GetLastUpdatedTimeInDateRangeCriteria>()).Returns(expected);
            fetch.FetchMatchIncidentUpdateTimeAsync(Arg.Any<short>(), Arg.Any<DateTime?>()).Returns(expectedMatches);

            await sync.SyncIncidentByUpdatedTimeAsync();
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }


        [Fact]
        public async Task SyncIncidents_WhenCalled_ReturnExpectedResult()
        {
            var param = fixture.Create<long[]>();
            var expected = fixture.Create<IDictionary<string, MatchIncidentResponse>>();
            fetch.FetchMultipleMatchIncidentsAsync(Arg.Any<short>(), Arg.Any<long[]>()).Returns(expected);

            await sync.SyncIncidents(param);
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }
    }
}