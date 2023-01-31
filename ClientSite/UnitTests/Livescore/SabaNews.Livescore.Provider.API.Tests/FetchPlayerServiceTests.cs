namespace SabaNews.Livescore.Provider.API.Tests
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Newtonsoft.Json;
    using NSubstitute;
    using SabaNews.Livescore.Provider.API.Models.Player;
    using SabaNews.Livescore.Provider.API.Services;
    using Xunit;

    public class FetchPlayerServiceTests
    {
        private readonly IHttpClientService httpClientService;
        private readonly Fixture fixture;
        private readonly FetchPlayerService fetchPlayer;

        public FetchPlayerServiceTests()
        {
            httpClientService = Substitute.For<IHttpClientService>();
            fixture = new Fixture();
            fetchPlayer = new FetchPlayerService(httpClientService);
        }

        [Fact]
        public async Task FetchPlayerListAsync_WhenCalled_ReturnExpectedResult()
        {
            var players = fixture.Create<Dictionary<string, List<PlayerListResponse>>>();
            httpClientService.ExecutePostAsync<Dictionary<string, List<PlayerListResponse>>>("/player/list", Arg.Any<Dictionary<string, object>>()).Returns(players);
            var result = await fetchPlayer.FetchPlayerListAsync(new FetchPlayerListParams());

            Assert.Equal(JsonConvert.SerializeObject(players), JsonConvert.SerializeObject(result));
        }
    }
}