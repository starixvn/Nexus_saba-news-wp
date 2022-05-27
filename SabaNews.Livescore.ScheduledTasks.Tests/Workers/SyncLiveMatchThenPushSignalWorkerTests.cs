namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Constants;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.Services;
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
            jobSettings.Add(new ScheduledTaskSettings
            {
                Enabled = true,
                Interval = 1000,
                Name = JobName.SyncLiveMatchAndPushSignalR
            });
            worker = new SyncLiveMatchThenPushSignalWorker(fetchMatchService, pushSignalService, syncMatchService,
                liveMatchService, matchService, jobSettings, appSettings, logService);
        }

        [Fact]
        public async Task DoWorkAsync_SyncMatchHasNoInfo_RunExpectedService()
        {
            var liveMatchResponse = fixture.Create<List<MatchDetailResponse>>();
            var preLiveMatch = new long[] { 1, 2, 3, 4 };
            var notExistedMatches = new long[] { 8, 9, 10 };
            fetchMatchService.FetchLiveMatchListAsync(Arg.Any<SyncMatchParams>()).Returns(liveMatchResponse);
            liveMatchService.GetCurrentLiveMatchAsync().Returns(preLiveMatch);
            liveMatchService.GetNotExistedMatchAsyc().Returns(notExistedMatches);
        }
    }
}