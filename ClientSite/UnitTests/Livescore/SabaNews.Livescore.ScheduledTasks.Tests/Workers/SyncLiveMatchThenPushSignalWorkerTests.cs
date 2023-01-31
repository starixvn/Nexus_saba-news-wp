namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Match;
    using SabaNews.Livescore.Services.Interfaces;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncLiveMatchThenPushSignalWorkerTests
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly IPushSignalService pushSignalService;
        private readonly ISyncMatchService syncMatchService;
        private readonly ILiveMatchService liveMatchService;
        private readonly IMatchService matchService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly List<ScheduledTaskSettings> jobSettings;
        private readonly ILogServiceProvider logService;
        private readonly SyncLiveMatchThenPushSignalWorker worker;

        public SyncLiveMatchThenPushSignalWorkerTests()
        {
            fetchMatchService = Substitute.For<IFetchMatchService>();
            pushSignalService = Substitute.For<IPushSignalService>();
            syncMatchService = Substitute.For<ISyncMatchService>();
            liveMatchService = Substitute.For<ILiveMatchService>();
            matchService = Substitute.For<IMatchService>();
            logService = Substitute.For<ILogServiceProvider>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            jobSettings = fixture.Create<List<ScheduledTaskSettings>>();
            worker = new SyncLiveMatchThenPushSignalWorker(fetchMatchService, pushSignalService, syncMatchService,
                liveMatchService, matchService, jobSettings, appSettings, logService);
        }

        [Fact]
        public async Task DoWorkAsync_SyncMatch_CallExpectedService()
        {
            var liveMatchResponse = new List<MatchDetailResponse>
            {
                new MatchDetailResponse {MatchId = 1},
                new MatchDetailResponse {MatchId = 2},
                new MatchDetailResponse {MatchId = 100}
            };
            var preLiveMatch = new long[] { 1, 2, 3, 4 };
            var notExistedMatches = new long[] { 8, 9, 10 };
            var matchResponse = fixture.Create<List<MatchResponse>>();
            fetchMatchService.FetchLiveMatchListAsync(Arg.Any<SyncMatchParams>()).Returns(liveMatchResponse);
            liveMatchService.GetCurrentLiveMatchAsync().Returns(preLiveMatch);
            liveMatchService.GetNotExistedMatchAsyc().Returns(notExistedMatches);
            fetchMatchService.FetchMatchListAsync(Arg.Any<SyncMatchParams>()).Returns(matchResponse);

            //actual
            await worker.DoWorkAsync();

            //Expected
            await fetchMatchService.Received().FetchMatchListAsync(Arg.Is<SyncMatchParams>(x => string.Join(",", x.MatchIds) == "3,4,8,9,10"));
            await syncMatchService.Received().SyncMatchByLastUpdatedTimeAsync(Arg.Is(matchResponse), Arg.Is(true));

            await matchService.Received(liveMatchResponse.Count).GetMatchByIdAsync(Arg.Any<GetMatchByIdCriteria>());
            await matchService.Received(liveMatchResponse.Count).GetMatchIncidentAsync(Arg.Any<GetMatchIncidentCriteria>());
            await matchService.Received(liveMatchResponse.Count).GetMatchStatisticAsync(Arg.Any<GetMatchStatisticCriteria>());
            await matchService.Received(liveMatchResponse.Count).GetHeadToHeadAsync(Arg.Any<GetHeadToHeadCriteria>());
            await matchService.Received(liveMatchResponse.Count).GetLineupAsync(Arg.Any<GetMatchLineupCriteria>());
            await pushSignalService.Received(liveMatchResponse.Count).PushMatchDetailcAsync(Arg.Any<long>(), Arg.Any<MatchDetail>());
        }
    }
}