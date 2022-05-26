namespace SabaNews.Livescore.Services.Tests.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Services.Criteria.League;
    using Xunit;

    public class LeagueServiceTests
    {
        private IBaseService baseService;

        [Fact]
        public async Task GetMajorLeagueAsync_WhenCalled_ReturnExpectedResult()
        {
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetMajorLeagueCriteria>()).Returns(leagues);
            var actual = await service.GetMajorLeagueAsync();
            Assert.Equal(leagues, actual);
        }

        private LeagueService GetService()
        {
            baseService = Substitute.For<IBaseService>();

            return new LeagueService(baseService);
        }
    }
}