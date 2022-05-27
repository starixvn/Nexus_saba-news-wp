namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.SyncData.Models.HeadToHead;
    using SabaNews.Livescore.SyncData.Services.Interfaces;
    using Xunit;

    public class SyncHeadToHeadFromCurrentDateToFutureWorkerTests
    {
        private readonly ISyncHeadToHeadService synch2hService;
        private readonly IFetchTeamService fetchTeamService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly List<ScheduledTaskSettings> jobSettings;
        private readonly ILogServiceProvider logService;
        private readonly SyncHeadToHeadFromCurrentDateToFutureWorker worker;

        public SyncHeadToHeadFromCurrentDateToFutureWorkerTests()
        {
            synch2hService = Substitute.For<ISyncHeadToHeadService>();
            fetchTeamService = Substitute.For<IFetchTeamService>();
            logService = Substitute.For<ILogServiceProvider>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            jobSettings = fixture.Create<List<ScheduledTaskSettings>>();
            worker = new SyncHeadToHeadFromCurrentDateToFutureWorker(synch2hService, fetchTeamService, jobSettings, appSettings, logService);
        }

        [Fact]
        public async Task DoWorkAsync_WhenNoMatchToGetH2H_WillNotCallSyncService()
        {
            synch2hService.GetMatchToSyncHeadToHeadByDateRange(
                Arg.Any<DateTime>(),
                Arg.Any<DateTime>(),
                Arg.Is(appSettings.SyncHeadToHeadRowLimit))
                .Returns(new List<MatchToGetH2H>());

            await worker.DoWorkAsync();
            await fetchTeamService.DidNotReceive().FetchHeadToHeadAsync(Arg.Any<long>(), Arg.Any<long>(), Arg.Any<long>());
            await synch2hService.DidNotReceive().SyncHeadToHeadAsync(Arg.Any<HeadToHeadResponse>());
            await synch2hService.DidNotReceive().UpdateSyncCompletedHeadToHead(Arg.Any<long>());
        }

        [Fact]
        public async Task DoWorkAsync_WhenHaveMatchToGetH2H_WillCallExpectedService()
        {
            var h2hMatches = fixture.Create<List<MatchToGetH2H>>();
            synch2hService.GetMatchToSyncHeadToHeadByDateRange(
                Arg.Any<DateTime>(),
                Arg.Any<DateTime>(),
                Arg.Is(appSettings.SyncHeadToHeadRowLimit))
                .Returns(h2hMatches);

            await worker.DoWorkAsync();
            await fetchTeamService.Received(h2hMatches.Count).FetchHeadToHeadAsync(Arg.Any<long>(), Arg.Any<long>(), Arg.Any<long>());
            await synch2hService.Received(h2hMatches.Count).SyncHeadToHeadAsync(Arg.Any<HeadToHeadResponse>());
            await synch2hService.Received(h2hMatches.Count).UpdateSyncCompletedHeadToHead(Arg.Any<long>());
        }
    }
}