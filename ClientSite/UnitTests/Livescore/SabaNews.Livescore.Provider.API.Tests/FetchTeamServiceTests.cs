namespace SabaNews.Livescore.Provider.API.Tests
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.Provider.API.Services;
    using Xunit;

    public class FetchTeamServiceTests
    {
        private readonly IHttpClientService httpClientService;
        private readonly Fixture fixture;
        private readonly FetchTeamService fetchTeamService;

        public FetchTeamServiceTests()
        {
            httpClientService = Substitute.For<IHttpClientService>();
            fixture = new Fixture();
            fetchTeamService = new FetchTeamService(httpClientService);
        }

        [Fact]
        public async Task FetchHeadToHeadAsync_WhenCalled_ReturnExpectedResult()
        {
            var h2h = fixture.Create<HeadToHeadResponse>();
            httpClientService.ExecutePostAsync<HeadToHeadResponse>(Arg.Is("/team/history"), Arg.Any<Dictionary<string, object>>()).Returns(h2h);
            var result = await fetchTeamService.FetchHeadToHeadAsync(1000, 12, 13);
            Assert.Equal(1000, result.SourceMatchId);
        }

        [Fact]
        public async Task FetchTeamListAsync_WhenCalled_ReturnExpectedResult()
        {
            var teams = fixture.Create<List<TeamListResponse>>();
            httpClientService.ExecutePostAsync<List<TeamListResponse>>(Arg.Is("/team/list"), Arg.Any<Dictionary<string, object>>()).Returns(teams);
            var result = await fetchTeamService.FetchTeamListAsync(new FetchTeamListParams());
            Assert.Equal(teams, result);
        }
    }
}