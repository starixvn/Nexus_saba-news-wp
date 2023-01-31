namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncMatchByUpdatedTimeInPastWorkerTests
    {
        private readonly ISyncMatchService syncMatchService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly List<ScheduledTaskSettings> jobSettings;
        private readonly ILogServiceProvider logService;
        private readonly SyncMatchByUpdatedTimeInPastWorker worker;

        public SyncMatchByUpdatedTimeInPastWorkerTests()
        {
            syncMatchService = Substitute.For<ISyncMatchService>();
            logService = Substitute.For<ILogServiceProvider>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            jobSettings = fixture.Create<List<ScheduledTaskSettings>>();
            worker = new SyncMatchByUpdatedTimeInPastWorker(syncMatchService, jobSettings, appSettings, logService);
        }

        [Fact]
        public async Task DoWorkAsync_WhenUpdatedTimeNotStuck_CallExpectedService()
        {
            var updatedAt = fixture.Create<DateTime>();
            syncMatchService.GetMatchLastUpdatedTimeInDateRangeAsync(Arg.Any<DateTime>(), Arg.Any<DateTime>(), Arg.Is(true)).Returns(updatedAt);

            //actual
            await worker.DoWorkAsync();

            //Expected

            await syncMatchService.Received().SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(Arg.Any<DateTime>(), Arg.Any<DateTime>(), Arg.Is(updatedAt));
        }
    }
}