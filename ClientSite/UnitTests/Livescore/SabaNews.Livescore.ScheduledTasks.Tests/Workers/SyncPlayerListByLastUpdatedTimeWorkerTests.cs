namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncPlayerListByLastUpdatedTimeWorkerTests
    {
        private readonly ISyncPlayerService syncPlayerService;
        private readonly Fixture fixture;
        private readonly List<ScheduledTaskSettings> jobSettings;
        private readonly ILogServiceProvider logService;
        private readonly SyncPlayerListByLastUpdatedTimeWorker worker;

        public SyncPlayerListByLastUpdatedTimeWorkerTests()
        {
            syncPlayerService = Substitute.For<ISyncPlayerService>();
            logService = Substitute.For<ILogServiceProvider>();
            fixture = new Fixture();
            jobSettings = fixture.Create<List<ScheduledTaskSettings>>();
            worker = new SyncPlayerListByLastUpdatedTimeWorker(syncPlayerService, jobSettings, logService);
        }

        [Fact]
        public async Task ExecuteJobAsync_WhenCalled_CalledExpectedFunction()
        {
            //Act
            await worker.DoWorkAsync();

            //Assert
            await syncPlayerService.Received().SyncPlayerListByLastUpdatedTimeAsync();
        }
    }
}