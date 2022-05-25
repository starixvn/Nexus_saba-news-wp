namespace SabaNews.Livescore.Provider.API.Tests
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Livescore.Provider.API.Models.League;
    using SabaNews.Livescore.Provider.API.Services;
    using Xunit;

    public class FetchLeagueServiceTests
    {
        private readonly IHttpClientService httpClientService;
        private readonly Fixture fixture;
        private readonly FetchLeagueService fetchLeague;

        public FetchLeagueServiceTests()
        {
            httpClientService = Substitute.For<IHttpClientService>();
            fixture = new Fixture();
            fetchLeague = new FetchLeagueService(httpClientService);
        }

        [Fact]
        public async Task FetchLeagueListAsync_WhenCalled_ReturnExpectedResult()
        {
            var leagues = fixture.Create<List<LeagueListResponse>>();
            httpClientService.ExecutePostAsync<List<LeagueListResponse>>("/league/list", Arg.Any<Dictionary<string, object>>()).Returns(leagues);
            var result = await fetchLeague.FetchLeagueListAsync(new FetchLeagueListParams());
            Assert.Equal(leagues, result);
        }
    }
}