namespace SabaNews.Livescore.Provider.API.Tests
{
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Livescore.Provider.API.Models.Country;
    using SabaNews.Livescore.Provider.API.Services;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class FetchCountryServiceTests
    {
        private readonly IHttpClientService httpClientService;
        private readonly Fixture fixture;
        private readonly FetchCountryService fetchCountry;

        public FetchCountryServiceTests()
        {
            httpClientService = Substitute.For<IHttpClientService>();
            fixture = new Fixture();
            fetchCountry = new FetchCountryService(httpClientService);
        }

        [Fact]
        public async Task FetchCountryListAsync_WhenCalled_ReturnExpectedResult()
        {
            var leagues = fixture.Create<List<CountryListResponse>>();
            httpClientService.ExecutePostAsync<List<CountryListResponse>>("/general/country", Arg.Any<Dictionary<string, object>>()).Returns(leagues);
            var result = await fetchCountry.FetchCountryListAsync();
            Assert.Equal(leagues, result);
        }
    }
}
