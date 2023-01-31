namespace SabaNews.Livescore.SyncData.Tests.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncTeamServiceTests
    {
        private readonly IBaseService baseService;
        private readonly IFetchTeamService fetchTeamService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly SyncTeamService syncTeamService;

        public SyncTeamServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fetchTeamService = Substitute.For<IFetchTeamService>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            syncTeamService = new SyncTeamService(appSettings, baseService, fetchTeamService);
        }

        [Fact]
        public async Task SyncTeamListByLastUpdatedTimeAsync_WhenAPI_HaveNoData_DoNotCallBaseServiceExecuteNonQuery()
        {
            fetchTeamService.FetchTeamListAsync(Arg.Any<FetchTeamListParams>()).Returns(new List<TeamListResponse>());
            await syncTeamService.SyncTeamListByLastUpdatedTimeAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncTeamListByLastUpdatedTimeAsync_WhenAPI_HasData_DoCallBaseServiceExecuteNonQuery()
        {
            var teams = fixture.Create<List<TeamListResponse>>();
            fetchTeamService.FetchTeamListAsync(Arg.Any<FetchTeamListParams>()).Returns(teams);
            await syncTeamService.SyncTeamListByLastUpdatedTimeAsync();
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncTeamListGreaterThanTeamIdAsync_WhenAPI_HaveNoData_DoNotCallBaseServiceExecuteNonQuery()
        {
            fetchTeamService.FetchTeamListAsync(Arg.Any<FetchTeamListParams>()).Returns(new List<TeamListResponse>());
            await syncTeamService.SyncTeamListGreaterThanTeamIdAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncTeamListGreaterThanTeamIdAsync_WhenAPI_HasData_DoCallBaseServiceExecuteNonQuery()
        {
            var teams = fixture.Create<List<TeamListResponse>>();
            fetchTeamService.FetchTeamListAsync(Arg.Any<FetchTeamListParams>()).Returns(teams);
            await syncTeamService.SyncTeamListGreaterThanTeamIdAsync();
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }
    }
}