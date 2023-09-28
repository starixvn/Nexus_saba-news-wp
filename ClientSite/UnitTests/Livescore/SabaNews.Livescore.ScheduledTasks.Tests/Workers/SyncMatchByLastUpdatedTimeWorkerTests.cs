namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.ScheduledTasks.Models;
    using SabaNews.Livescore.ScheduledTasks.Workers;
    using SabaNews.Livescore.SyncData.Services;
    using Xunit;

    public class SyncMatchByLastUpdatedTimeWorkerTests
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncMatchService syncMatchService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly List<ScheduledTaskSettings> jobSettings;
        private readonly ILogServiceProvider logService;
        private readonly SyncMatchByLastUpdatedTimeWorker worker;

        public SyncMatchByLastUpdatedTimeWorkerTests()
        {
            fetchMatchService = Substitute.For<IFetchMatchService>();
            syncMatchService = Substitute.For<ISyncMatchService>();
            logService = Substitute.For<ILogServiceProvider>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            appSettings.IsSyncIncident = true;
            jobSettings = fixture.Create<List<ScheduledTaskSettings>>();
            worker = new SyncMatchByLastUpdatedTimeWorker(syncMatchService, fetchMatchService, appSettings, jobSettings, logService);
        }

        [Fact]
        public async Task DoWorkAsync_WhenUpdatedTimeNotStuck_CallExpectedService()
        {
            var matchResponse = fixture.Create<List<MatchResponse>>();
            var lastUpdatedTime = fixture.Create<DateTime>();
            var current = DateTime.UtcNow;
            var lastday = lastUpdatedTime.AddDays(-1);
            matchResponse.First().IsLineUp = true;
            fetchMatchService.FetchMatchListAsync(Arg.Any<SyncMatchParams>()).Returns(matchResponse);
            syncMatchService.GetMatchLastUpdatedTimeAsync().Returns(lastUpdatedTime);

            //actual
            await worker.DoWorkAsync();

            //Expected
            await syncMatchService.Received().SyncMatchByLastUpdatedTimeAsync(Arg.Is(matchResponse));
            await fetchMatchService.Received().FetchMatchDetailAsync(Arg.Is<SyncMatchParams>(x => x.MatchIds.Length == matchResponse.Count));
            await fetchMatchService.Received().FetchMatchLineupAsync(Arg.Is<long[]>(x => x.Length == matchResponse.Count(m => m.IsLineUp)));
        }

        [Fact]
        public async Task DoWorkAsync_WhenUpdatedTimeStuck_CallExpectedService()
        {
            var date = new DateTime(2020, 1, 1);
            var matchResponse = fixture.Create<List<MatchResponse>>();
            foreach (var item in matchResponse)
            {
                item.UpdatedAt = date;
            }

            fetchMatchService.FetchMatchListAsync(Arg.Any<SyncMatchParams>()).Returns(matchResponse);
            syncMatchService.GetMatchLastUpdatedTimeAsync().Returns(date);
            //actual
            await worker.DoWorkAsync();

            //Expected
            await syncMatchService.Received(2).GetMatchLastUpdatedTimeInDateRangeAsync(Arg.Any<DateTime>(), Arg.Any<DateTime>(), Arg.Any<bool>());
            await syncMatchService.Received(2).SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(Arg.Any<DateTime>(), Arg.Any<DateTime>(), Arg.Any<DateTime>());
        }
    }
}