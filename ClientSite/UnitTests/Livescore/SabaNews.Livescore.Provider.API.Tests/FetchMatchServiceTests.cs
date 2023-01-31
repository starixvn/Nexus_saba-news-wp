namespace SabaNews.Livescore.Provider.API.Tests
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using Xunit;

    public class FetchMatchServiceTests
    {
        private readonly IHttpClientService httpClientService;
        private readonly Fixture fixture;
        private readonly FetchMatchService fetchMatchService;

        public FetchMatchServiceTests()
        {
            httpClientService = Substitute.For<IHttpClientService>();
            fixture = new Fixture();
            fetchMatchService = new FetchMatchService(httpClientService);
        }

        [Fact]
        public async Task FetchMatchListAsync_WhenCalled_ReturnExpectedResult()
        {
            var matches = fixture.Create<List<MatchResponse>>();
            httpClientService.ExecutePostAsync<List<MatchResponse>>(Arg.Is("/match/list"), Arg.Any<Dictionary<string, object>>()).Returns(matches);
            var result = await fetchMatchService.FetchMatchListAsync(fixture.Create<SyncMatchParams>());
            Assert.Equal(matches, result);
        }

        [Fact]
        public async Task FetchMatchDetailAsync_WhenCalled_ReturnExpectedResult()
        {
            var matches = fixture.Create<List<MatchDetailResponse>>();
            httpClientService.ExecutePostAsync<List<MatchDetailResponse>>(Arg.Is("/match/detail"), Arg.Any<Dictionary<string, object>>()).Returns(matches);
            var result = await fetchMatchService.FetchMatchDetailAsync(fixture.Create<SyncMatchParams>());
            Assert.Equal(matches, result);
        }

        [Fact]
        public async Task FetchMatchLineupAsync_WhenCalled_ReturnExpectedResult()
        {
            var lineups = fixture.Create<List<MatchLineupResponse>>();
            httpClientService.ExecutePostAsync<List<MatchLineupResponse>>(Arg.Is("/lineup/list"), Arg.Any<Dictionary<string, object>>()).Returns(lineups);
            var result = await fetchMatchService.FetchMatchLineupAsync(fixture.Create<long[]>());
            Assert.Equal(lineups, result);
        }

        [Fact]
        public async Task FetchMatchCommentaryAsync_WhenCalled_ReturnExpectedResult()
        {
            var commentaries = fixture.Create<List<MatchCommentary>>();
            foreach (var item in commentaries)
            {
                item.MatchId = 12345;
            }
            httpClientService.ExecutePostAsync<MatchCommentaryResponse>(Arg.Is("/match/textlive_en"), Arg.Any<Dictionary<string, object>>())
                .Returns(new MatchCommentaryResponse { Commentaries = commentaries });
            var result = await fetchMatchService.FetchMatchCommentaryAsync(12345);
            Assert.Equal(commentaries, result);
        }

        [Fact]
        public async Task FetchMatchIncidentAsync_WhenCalled_ReturnExpectedResult()
        {
            var incidents = fixture.Create<List<MatchIncident>>();
            foreach (var item in incidents)
            {
                item.MatchId = 12345;
            }
            httpClientService.ExecutePostAsync<MatchIncidentResponse>(Arg.Is("/match/textlive"), Arg.Any<Dictionary<string, object>>())
                .Returns(new MatchIncidentResponse { Incidents = incidents });
            var result = await fetchMatchService.FetchMatchIncidentAsync(1, 12345);
            Assert.Equal(incidents, result);
        }

        [Fact]
        public async Task FetchMatchIncidentUpdateTimeAsync_WhenCalled_ReturnExpectedResult()
        {
            var incidents = fixture.Create<List<long>>();
            httpClientService.ExecutePostAsync<MatchIncidentUpdateTimeResponse>(Arg.Is("/match/textlive"), Arg.Any<Dictionary<string, object>>())
                .Returns(new MatchIncidentUpdateTimeResponse { UpdatedMatches = incidents });
            var result = await fetchMatchService.FetchMatchIncidentUpdateTimeAsync(1, DateTime.Now);
            Assert.Equal(incidents, result);
        }

        [Fact]
        public async Task FetchMatchIncidentUpdateTimeAsync_WhenCalled_ReturnEmpty()
        {
            List<long> incidents = null;
            httpClientService.ExecutePostAsync<MatchIncidentUpdateTimeResponse>(Arg.Is("/match/textlive"), Arg.Any<Dictionary<string, object>>())
                .Returns(new MatchIncidentUpdateTimeResponse { UpdatedMatches = incidents });
            var result = await fetchMatchService.FetchMatchIncidentUpdateTimeAsync(1, DateTime.Now);
            Assert.Equal(result, new List<long>());
        }

        [Fact]
        public async Task FetchLiveMatchListAsync_WhenCalled_ReturnExpectedResult()
        {
            var liveMatches = fixture.Create<List<MatchDetailResponse>>();

            httpClientService.ExecutePostAsync<List<MatchDetailResponse>>(Arg.Is("/match/lives"), Arg.Any<Dictionary<string, object>>())
                .Returns(liveMatches);
            var result = await fetchMatchService.FetchLiveMatchListAsync(new SyncMatchParams { MatchIds = new long[] { 1, 23 } });
            Assert.Equal(liveMatches, result);
        }

        [Fact]
        public async Task FetchDeletedMatchAsync_WhenCalled_ReturnExpectedResult()
        {
            var liveMatches = fixture.Create<List<DeletedMatch>>();

            httpClientService.ExecutePostAsync<List<DeletedMatch>>(Arg.Is("/match/deleted_list"), Arg.Any<Dictionary<string, object>>())
                .Returns(liveMatches);
            var result = await fetchMatchService.FetchDeletedMatchAsync();
            Assert.Equal(liveMatches, result);
        }
    }
}