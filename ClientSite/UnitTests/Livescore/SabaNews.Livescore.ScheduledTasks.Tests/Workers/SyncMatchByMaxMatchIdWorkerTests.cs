namespace SabaNews.Livescore.ScheduledTasks.Tests.Workers
{
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

    public class SyncMatchByMaxMatchIdWorkerTests
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly ISyncMatchService syncMatchService;
        private readonly AppSettings appSettings;
        private readonly Fixture fixture;
        private readonly List<ScheduledTaskSettings> jobSettings;
        private readonly ILogServiceProvider logService;
        private readonly SyncMatchByMaxMatchIdWorker worker;

        public SyncMatchByMaxMatchIdWorkerTests()
        {
            fetchMatchService = Substitute.For<IFetchMatchService>();
            syncMatchService = Substitute.For<ISyncMatchService>();
            logService = Substitute.For<ILogServiceProvider>();
            fixture = new Fixture();
            appSettings = fixture.Create<AppSettings>();
            jobSettings = fixture.Create<List<ScheduledTaskSettings>>();
            worker = new SyncMatchByMaxMatchIdWorker(syncMatchService, fetchMatchService, appSettings, jobSettings, logService);
        }

        [Fact]
        public async Task DoWorkAsync_WhenUpdatedTimeNotStuck_CallExpectedService()
        {
            var matchResponse = fixture.Create<List<MatchResponse>>();
            fetchMatchService.FetchMatchListAsync(Arg.Any<SyncMatchParams>()).Returns(matchResponse);

            //actual
            await worker.DoWorkAsync();

            //Expected
            await syncMatchService.Received().SyncMatchByLastUpdatedTimeAsync(Arg.Is(matchResponse));
            await fetchMatchService.Received().FetchMatchDetailAsync(Arg.Is<SyncMatchParams>(x => x.MatchIds.Length == matchResponse.Count));
            await fetchMatchService.Received().FetchMatchLineupAsync(Arg.Is<long[]>(x => x.Length == matchResponse.Count(m => m.IsLineUp)));
            await fetchMatchService.Received().FetchMatchIncidentAsync(Arg.Is((short)1), Arg.Any<long>());
        }
    }
}