namespace SabaNews.Livescore.Services.Tests.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
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

        [Fact]
        public async Task GetLeagueByIdsAsync_WhenCalled_ReturnExpectedResult()
        {
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetLeagueByIdsCriteria>()).Returns(leagues);
            var actual = await service.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria());
            Assert.Equal(leagues, actual);
        }

        [Fact]
        public async Task GetLeaguesAsync_WhenCalled_ReturnExpectedResult()
        {
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetLeaguesCriteria>()).Returns(leagues);
            var actual = await service.GetLeaguesAsync();
            Assert.Equal(leagues, actual);
        }

        [Fact]
        public async Task GetAllLeaguesAsync_WhenCalled_ReturnExpectedResult()
        {
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetAllLeaguesCriteria>()).Returns(leagues);
            var actual = await service.GetAllLeaguesAsync();
            Assert.Equal(leagues, actual);
        }

        [Fact]
        public async Task UpdateLeaguesExtraAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var model = fixture.Create<UpdateLeagueExtraCriteria>();
            var league = fixture.Create<League>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateLeagueExtraCriteria>(x => model = x)).Returns(expected);
            //Act
            var actual = await service.UpdateLeaguesExtraAsync(league);

            //Assert
            Assert.True(actual.IsSuccessful);
        }

        [Fact]
        public async Task DeleteLeaguesExtraAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var model = fixture.Create<DeleteLeagueExtraCriteria>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<DeleteLeagueExtraCriteria>(x => model = x)).Returns(expected);
            //Act
            var actual = await service.DeleteLeaguesExtraAsync(model.LeagueId);

            //Assert
            Assert.True(actual.IsSuccessful);
        }

        [Fact]
        public async Task InsertLeaguesExtraAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var model = fixture.Create<InsertLeagueExtraCriteria>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<InsertLeagueExtraCriteria>(x => model = x)).Returns(expected);
            //Act
            var actual = await service.InsertLeaguesExtraAsync(model.LeagueId);

            //Assert
            Assert.True(actual.IsSuccessful);
        }

        private LeagueService GetService()
        {
            baseService = Substitute.For<IBaseService>();

            return new LeagueService(baseService);
        }
    }
}