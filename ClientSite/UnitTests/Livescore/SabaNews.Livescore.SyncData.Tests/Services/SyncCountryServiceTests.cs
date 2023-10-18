namespace SabaNews.Livescore.SyncData.Tests.Services
{
    using AutoFixture;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Country;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Services;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class SyncCountryServiceTests
    {
        private readonly IBaseService baseService;
        private readonly IFetchCountryService fetchCountryService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly SyncCountryService syncService;

        public SyncCountryServiceTests()
        {
            baseService = Substitute.For<IBaseService>();
            fetchCountryService = Substitute.For<IFetchCountryService>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            syncService = new SyncCountryService(baseService, fetchCountryService);
        }

        [Fact]
        public async Task SyncCountryListAsync_WhenAPI_HaveNoData_DoNotCallBaseServiceExecuteNonQuery()
        {
            fetchCountryService.FetchCountryListAsync().Returns(new List<CountryListResponse>());
            await syncService.SyncCountryListAsync();
            await baseService.DidNotReceive().ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }

        [Fact]
        public async Task SyncLeagueListByLastUpdatedTimeAsync_WhenAPI_HasData_DoCallBaseServiceExecuteNonQuery()
        {
            var countries = fixture.Create<List<CountryListResponse>>();
            fetchCountryService.FetchCountryListAsync().Returns(countries);
            await syncService.SyncCountryListAsync();
            await baseService.Received(1).ExecuteNonQueryAsync(Arg.Any<INonQueryCommand>());
        }
    }
}
