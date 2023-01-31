namespace SabaNews.Livescore.SyncData.Tests.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Player;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncPlayerServiceTests
    {
        private readonly IBaseService baseService;
        private readonly IFetchPlayerService fetch;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly SyncPlayerService sync;

        public SyncPlayerServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fetch = Substitute.For<IFetchPlayerService>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            sync = new SyncPlayerService(appSettings, baseService, fetch);
        }

        [Fact]
        public async Task SyncPlayerListByLastUpdatedTimeAsync_WhenAPI_HaveNoData_DoNotCallBaseServiceExecuteNonQuery()
        {
            fetch.FetchPlayerListAsync(Arg.Any<FetchPlayerListParams>()).Returns(new Dictionary<string, List<PlayerListResponse>>());
            await sync.SyncPlayerListByLastUpdatedTimeAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncPlayerListByLastUpdatedTimeAsync_WhenAPI_HasData_DoCallBaseServiceExecuteNonQuery()
        {
            var players = fixture.Create<IDictionary<string, List<PlayerListResponse>>>();
            fetch.FetchPlayerListAsync(Arg.Any<FetchPlayerListParams>()).Returns(players);
            await sync.SyncPlayerListByLastUpdatedTimeAsync();
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }
    }
}