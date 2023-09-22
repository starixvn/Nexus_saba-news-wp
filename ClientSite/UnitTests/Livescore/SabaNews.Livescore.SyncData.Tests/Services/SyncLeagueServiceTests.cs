namespace SabaNews.Livescore.SyncData.Tests.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.League;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncLeagueServiceTests
    {
        private readonly IBaseService baseService;
        private readonly IFetchLeagueService fetchLeagueService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly SyncLeagueService syncService;

        public SyncLeagueServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fetchLeagueService = Substitute.For<IFetchLeagueService>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            syncService = new SyncLeagueService(appSettings, baseService, fetchLeagueService);
        }

        [Fact]
        public async Task SyncLeagueListByLastUpdatedTimeAsync_WhenAPI_HaveNoData_DoNotCallBaseServiceExecuteNonQuery()
        {
            fetchLeagueService.FetchLeagueListAsync(Arg.Any<FetchLeagueListParams>()).Returns(new List<LeagueListResponse>());
            await syncService.SyncLeagueListByLastUpdatedTimeAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncLeagueListByLastUpdatedTimeAsync_WhenAPI_HasData_DoCallBaseServiceExecuteNonQuery()
        {
            var leagues = fixture.Create<List<LeagueListResponse>>();
            fetchLeagueService.FetchLeagueListAsync(Arg.Any<FetchLeagueListParams>()).Returns(leagues);
            await syncService.SyncLeagueListByLastUpdatedTimeAsync();
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncLeagueListGreaterThanLeagueIdAsync_WhenAPI_HaveNoData_DoNotCallBaseServiceExecuteNonQuery()
        {
            fetchLeagueService.FetchLeagueListAsync(Arg.Any<FetchLeagueListParams>()).Returns(new List<LeagueListResponse>());
            await syncService.SyncLeagueListGreaterThanLeagueIdAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncLeagueListGreaterThanLeagueIdAsync_WhenAPI_HasData_DoCallBaseServiceExecuteNonQuery()
        {
            var leagues = fixture.Create<List<LeagueListResponse>>();
            fetchLeagueService.FetchLeagueListAsync(Arg.Any<FetchLeagueListParams>()).Returns(leagues);
            await syncService.SyncLeagueListGreaterThanLeagueIdAsync();
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncLeagueSeasonByIdsAsync_WhenAPI_HaveNoData_DoNotCallBaseServiceExecuteNonQuery()
        {
            var leagueIds = fixture.Create<long?[]>();
            fetchLeagueService.FetchLeagueListAsync(Arg.Any<FetchLeagueListParams>()).Returns(new List<LeagueListResponse>());
            await syncService.SyncLeagueSeasonByIdsAsync(leagueIds);
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncLeagueSeasonByIdsAsync_WhenAPI_HasData_DoCallBaseServiceExecuteNonQuery()
        {
            var leagueIds = fixture.Create<long?[]>();
            var leagues = fixture.Create<List<LeagueListResponse>>();
            fetchLeagueService.FetchLeagueListAsync(Arg.Any<FetchLeagueListParams>()).Returns(leagues);
            await syncService.SyncLeagueSeasonByIdsAsync(leagueIds);
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }
    }
}