namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncDeleledMatchesWorkerTests
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncMatchService syncMatchService;
        private readonly Fixture fixture;
        private readonly List<ScheduledTaskSettings> jobSettings;
        private readonly ILogServiceProvider logService;
        private readonly SyncDeleledMatchesWorker worker;

        public SyncDeleledMatchesWorkerTests()
        {
            fetchMatchService = Substitute.For<IFetchMatchService>();
            syncMatchService = Substitute.For<ISyncMatchService>();
            logService = Substitute.For<ILogServiceProvider>();
            fixture = new Fixture();
            jobSettings = fixture.Create<List<ScheduledTaskSettings>>();
            worker = new SyncDeleledMatchesWorker(syncMatchService, fetchMatchService, jobSettings, logService);
        }

        [Fact]
        public async Task ExecuteJobAsync_WhenCalled_CalledExpectedFunction()
        {
            var delMatches = fixture.Create<List<DeletedMatch>>();
            fetchMatchService.FetchDeletedMatchAsync().Returns(delMatches);
            //Act
            await worker.DoWorkAsync();

            //Assert
            await fetchMatchService.Received().FetchDeletedMatchAsync();
            await syncMatchService.Received().SyncDeletedMatchesAsync(Arg.Is(delMatches));
        }
    }
}