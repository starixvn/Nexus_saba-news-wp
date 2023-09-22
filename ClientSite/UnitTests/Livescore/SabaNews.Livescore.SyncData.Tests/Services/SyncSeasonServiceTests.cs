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
    using SabaNews.Livescore.Provider.API.Models.Season;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Season;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncSeasonServiceTests
    {
        private readonly IBaseService baseService;
        private readonly IFetchSeasonService fetch;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly SyncSeasonService sync;
        private readonly ISyncLeagueService syncLeagueService;

        public SyncSeasonServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fetch = Substitute.For<IFetchSeasonService>();
            syncLeagueService = Substitute.For<ISyncLeagueService>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            appSettings.MinDateSyncLivescoreData = DateTime.Now.AddDays(-100);
            sync = new SyncSeasonService(appSettings, baseService, fetch, syncLeagueService);
        }

        [Fact]
        public async Task SyncSeasonListByLastUpdatedTimeAsync_FetchNoData_DoNotCallExecuteNonQuery()
        {
            await sync.SyncSeasonListByLastUpdatedTimeAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncSeasonListByLastUpdatedTimeAsync_HasData_CallExecuteNonQuery()
        {
            var seasons = fixture.Create<List<SeasonListResponse>>();
            seasons.FirstOrDefault().SportId = 1;
            fetch.FetchSeasonListAsync(Arg.Any<FetchSeasonListParams>()).Returns(seasons);
            await sync.SyncSeasonListByLastUpdatedTimeAsync();
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
            await syncLeagueService.Received().SyncLeagueSeasonByIdsAsync(Arg.Any<long?[]>());
        }

        [Fact]
        public async Task SyncStageListByLastUpdatedTimeAsync_FetchNoData_DoNotCallExecuteNonQuery()
        {
            await sync.SyncStageListByLastUpdatedTimeAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncStageListByLastUpdatedTimeAsync_HasData_CallExecuteNonQuery()
        {
            var seasons = fixture.Create<List<StageListResponse>>();
            seasons.FirstOrDefault().SportId = 1;
            fetch.FetchStageListAsync(Arg.Any<FetchSeasonListParams>()).Returns(seasons);
            await sync.SyncStageListByLastUpdatedTimeAsync();
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncSeasonStandingsByLastUpdatedTimeAsync_WhenCalled_NoUpdatedSeasonId_WillNotCallExecuteNonQuery()
        {
            await sync.SyncSeasonStandingsByLastUpdatedTimeAsync();
            await fetch.Received().FetchSeasonStandingsAsync<UpdatedSeasonIdListResponse>(Arg.Any<FetchSeasonStandingsParams>());
            await fetch.DidNotReceive().FetchSeasonStandingsAsync<SeasonStandingsResponse>(Arg.Any<FetchSeasonStandingsParams>());
            await fetch.DidNotReceive().FetchSeasonStandingsAsync<SeasonStandingsResponse>(Arg.Any<FetchSeasonStandingsParams>());
        }

        [Fact]
        public async Task SyncSeasonStandingsByLastUpdatedTimeAsync_WhenCalled_HasUpdatedSeasonId_WillCallExpectedService()
        {
            var updatedSeasonIds = fixture.Create<List<UpdatedSeasonIdListResponse>>();
            baseService.GetDataEntityAsync<DateTime?>(Arg.Any<GetSeasonStandingsLastUpdatedTimeCriteria>()).Returns(appSettings.MinDateSyncLivescoreData);
            fetch.FetchSeasonStandingsAsync<UpdatedSeasonIdListResponse>(Arg.Any<FetchSeasonStandingsParams>()).Returns(updatedSeasonIds);
            fetch.FetchSeasonStandingsAsync<SeasonStandingsResponse>(Arg.Any<FetchSeasonStandingsParams>()).Returns(fixture.Create<List<SeasonStandingsResponse>>());
            await sync.SyncSeasonStandingsByLastUpdatedTimeAsync();

            await fetch.Received().FetchSeasonStandingsAsync<UpdatedSeasonIdListResponse>(Arg.Any<FetchSeasonStandingsParams>());
            await fetch.Received().FetchSeasonStandingsAsync<SeasonStandingsResponse>(Arg.Any<FetchSeasonStandingsParams>());
            await fetch.Received().FetchSeasonStandingsAsync<SeasonStandingsResponse>(Arg.Any<FetchSeasonStandingsParams>());
        }

        [Fact]
        public async Task SyncSeasonTeamHonorByLastUpdatedTimeAsync_FetchNoData_DoNotCallExecuteNonQuery()
        {
            await sync.SyncSeasonTeamHonorByLastUpdatedTimeAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncSeasonTeamHonorByLastUpdatedTimeAsync_HasData_CallExecuteNonQuery()
        {
            var seasons = fixture.Create<List<TeamHonorResponse>>();
            fetch.FetchTeamHonorAsync(Arg.Any<FetchTeamHonorParams>()).Returns(seasons);
            await sync.SyncSeasonTeamHonorByLastUpdatedTimeAsync();
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncSeasonPlayerHonorByLastUpdatedTimeAsync_FetchNoData_DoNotCallExecuteNonQuery()
        {
            await sync.SyncSeasonPlayerHonorByLastUpdatedTimeAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncSeasonPlayerHonorByLastUpdatedTimeAsync_HasData_CallExecuteNonQuery()
        {
            var seasons = fixture.Create<List<PlayerHonorResponse>>();
            fetch.FetchPlayerHonorAsync(Arg.Any<FetchTeamHonorParams>()).Returns(seasons);
            await sync.SyncSeasonPlayerHonorByLastUpdatedTimeAsync();
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }
    }
}