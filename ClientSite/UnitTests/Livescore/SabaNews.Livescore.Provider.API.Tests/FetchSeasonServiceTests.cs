namespace SabaNews.Livescore.Provider.API.Tests
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using Newtonsoft.Json;
    using NSubstitute;
    using SabaNews.Livescore.Provider.API.Models.Season;
    using SabaNews.Livescore.Provider.API.Services;
    using Xunit;

    public class FetchSeasonServiceTests
    {
        private readonly IHttpClientService httpClientService;
        private readonly Fixture fixture;
        private readonly FetchSeasonService fetchSeason;

        public FetchSeasonServiceTests()
        {
            httpClientService = Substitute.For<IHttpClientService>();
            fixture = new Fixture();
            fetchSeason = new FetchSeasonService(httpClientService);
        }

        [Fact]
        public async Task FetchSeasonListAsync_WhenCalled_ReturnExpectedResult()
        {
            var seasonsList = fixture.Create<List<SeasonListResponse>>();
            httpClientService.ExecutePostAsync<List<SeasonListResponse>>("/season/list", Arg.Any<Dictionary<string, object>>()).Returns(seasonsList);
            var actual = await fetchSeason.FetchSeasonListAsync(new FetchSeasonListParams());
            Assert.Equal(seasonsList, actual);
        }

        [Fact]
        public async Task FetchStageListAsync_WhenCalled_ReturnExpectedResult()
        {
            var seasonsList = fixture.Create<List<StageListResponse>>();
            httpClientService.ExecutePostAsync<List<StageListResponse>>("/stage/list", Arg.Any<Dictionary<string, object>>()).Returns(seasonsList);
            var actual = await fetchSeason.FetchStageListAsync(new FetchSeasonListParams());
            Assert.Equal(seasonsList, actual);
        }

        [Fact]
        public async Task FetchTeamHonorAsync_WhenCalled_ReturnExpectedResult()
        {
            var seasonsList = fixture.Create<List<TeamHonorResponse>>();
            httpClientService.ExecutePostAsync<List<TeamHonorResponse>>("/season/honor/team/football", Arg.Any<Dictionary<string, object>>()).Returns(seasonsList);
            var actual = await fetchSeason.FetchTeamHonorAsync(new FetchTeamHonorParams());
            Assert.Equal(JsonConvert.SerializeObject(seasonsList), JsonConvert.SerializeObject(actual));
        }

        [Fact]
        public async Task FetchSeasonStandingsAsync_WhenCalled_ReturnExpectedResult()
        {
            var seasonsList = fixture.Create<List<object>>();
            httpClientService.ExecutePostAsync<List<object>>("/season/board/team", Arg.Any<Dictionary<string, object>>()).Returns(seasonsList);
            var actual = await fetchSeason.FetchSeasonStandingsAsync<object>(new FetchSeasonStandingsParams());
            Assert.Equal(JsonConvert.SerializeObject(seasonsList), JsonConvert.SerializeObject(actual));
        }

        [Fact]
        public async Task FetchPlayerHonorAsync_WhenCalled_ReturnExpectedResult()
        {
            var seasonsList = fixture.Create<List<PlayerHonorResponse>>();
            httpClientService.ExecutePostAsync<List<PlayerHonorResponse>>("/season/honor/player/football", Arg.Any<Dictionary<string, object>>()).Returns(seasonsList);
            var actual = await fetchSeason.FetchPlayerHonorAsync(new FetchTeamHonorParams());
            Assert.Equal(seasonsList, actual);
        }
    }
}