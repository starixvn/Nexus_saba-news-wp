namespace SabaNews.Livescore.Services.Tests.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Reflection;
    using System.Threading.Tasks;
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Livescore.Services.Criteria.League;
    using Xunit;

    public class LeagueServiceTests
    {
        private IBaseService baseService;
        private readonly List<League> league = new()
        {
                new League
                {
                    LeagueId = 1,
                    LeagueFullNameEN = "Test1",
                    LeagueNameVN = "Test1",
                    IsMajor = true,
                    Priority = 1,
                    CreatedDate = DateTime.Parse("08/18/2018 07:22:16")
                },
                new League
                {
                    LeagueId = 1,
                    LeagueFullNameEN = "Test1",
                    LeagueNameVN = "Test1",
                    IsMajor = false,
                    Priority = 2,
                    CreatedDate = DateTime.Parse("08/18/2018 08:22:16")
                }
            };

        [Fact]
        public async Task GetMajorLeagueAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetMajorLeagueCriteria>()).Returns(leagues);
            //Act
            var actual = await service.GetMajorLeagueAsync();
            //Assert
            Assert.Equal(leagues, actual);
        }

        [Fact]
        public async Task GetLeagueByIdsAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetLeagueByIdsCriteria>()).Returns(leagues);
            //Act
            var actual = await service.GetLeagueByIdsAsync(new GetLeagueByIdsCriteria());
            //Assert
            Assert.Equal(leagues, actual);
        }

        [Fact]
        public async Task GetPlayerByIdTeamAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var players = fixture.Create<IList<Player>>();
            var teamId = fixture.Create<long>();
            baseService.GetDataAsync<Player>(Arg.Any<GetPlayerByTeamIdCriteria>()).Returns(players);
            //Act
            var actual = await service.GetPlayerByIdTeamAsync(teamId);
            //Assert
            Assert.Equal(players, actual);
        }

        [Fact]
        public async Task GetTop10ScorersAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var topScorers = fixture.Create<IList<TopScorers>>();
            var scorersCriteria = fixture.Create<GetTop10ScorersCriteria>();
            baseService.GetDataAsync<TopScorers>(Arg.Any<GetTop10ScorersCriteria>()).Returns(topScorers);
            //Act
            var actual = await service.GetTop10ScorersAsync(scorersCriteria);
            //Assert
            Assert.Equal(topScorers, actual);
        }

        [Fact]
        public async Task GetAllTeamAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var teams = fixture.Create<IList<Team>>();
            baseService.GetDataAsync<Team>(Arg.Any<GetAllTeamCriteria>()).Returns(teams);
            //Act
            var actual = await service.GetAllTeamAsync();
            //Assert
            Assert.Equal(teams, actual);
        }

        [Fact]
        public async Task GetAllTopScorersAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var topScorers = fixture.Create<IList<TopScorers>>();
            baseService.GetDataAsync<TopScorers>(Arg.Any<GetAllTopScorersCriteria>()).Returns(topScorers);
            //Act
            var actual = await service.GetAllTopScorersAsync();
            //Assert
            Assert.Equal(topScorers, actual);
        }

        [Fact]
        public async Task GetLeaguesAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetLeaguesCriteria>()).Returns(leagues);
            //Act
            var actual = await service.GetLeaguesAsync();
            //Assert
            Assert.Equal(leagues, actual);
        }

        [Fact]
        public async Task GetAllLeaguesAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var leagues = fixture.Create<IList<League>>();
            baseService.GetDataAsync<League>(Arg.Any<GetAllLeaguesCriteria>()).Returns(leagues);
            //Act
            var actual = await service.GetAllLeaguesAsync();
            //Assert
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
            var leagues = fixture.Create<IList<League>>();
            var expected = new CommandResult { Code = 0 };
            baseService.GetDataAsync<League>(Arg.Any<GetAllLeaguesCriteria>()).Returns(leagues);
            baseService.GetDataAsync<League>(Arg.Any<GetLeagueByIdsCriteria>()).Returns(leagues);
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
            var leagues = fixture.Create<IList<League>>();
            var expected = new CommandResult { Code = 0 };
            baseService.GetDataAsync<League>(Arg.Any<GetAllLeaguesCriteria>()).Returns(leagues);
            baseService.GetDataAsync<League>(Arg.Any<GetLeagueByIdsCriteria>()).Returns(leagues);
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

        [Fact]
        public async Task InsertTopScorerAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var model = fixture.Create<InsertTopScorersCriteria>();
            var topScorers = fixture.Create<TopScorers>();
            var modelPriority = fixture.Create<UpdatePriorityTopScorersCriteria>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<InsertTopScorersCriteria>(x => model = x)).Returns(expected);
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdatePriorityTopScorersCriteria>(x => modelPriority = x)).Returns(expected);
            //Act
            var actual = await service.InsertTopScorerAsync(topScorers);
            //Assert
            Assert.True(actual.IsSuccessful);
        }

        [Fact]
        public async Task UpdateTopScorerAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var model = fixture.Create<UpdateTopScorersCriteria>();
            var topScorersList = fixture.Create<IList<TopScorers>>();
            var topScorers = fixture.Create<TopScorers>();
            var modelPriority = fixture.Create<UpdatePriorityTopScorersCriteria>();
            var expected = new CommandResult { Code = 0 };

            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateTopScorersCriteria>(x => model = x)).Returns(expected);
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdatePriorityTopScorersCriteria>(x => modelPriority = x)).Returns(expected);
            baseService.GetDataAsync<TopScorers>(Arg.Any<GetTopScorersByRowNumCriteria>()).Returns(topScorersList);
            //Act
            var actual = await service.UpdateTopScorerAsync(topScorers);
            //Assert
            Assert.True(actual.IsSuccessful);
        }

        [Fact]
        public async Task UpdateLeaguesAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var model = fixture.Create<UpdateLeagueCriteria>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateLeagueCriteria>(x => model = x)).Returns(expected);
            //Act
            var actual = await service.UpdateLeaguesAsync(model);
            //Assert
            Assert.True(actual.IsSuccessful);
        }

        [Fact]
        public async Task GetTopScorersByRowNumAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var topScorers = fixture.Create<IList<TopScorers>>();
            var rowNum = fixture.Create<int>();
            baseService.GetDataAsync<TopScorers>(Arg.Any<GetTopScorersByRowNumCriteria>()).Returns(topScorers);
            //Act
            var actual = await service.GetTopScorersByRowNumAsync(rowNum);
            //Assert
            Assert.Equal(topScorers, actual);
        }

        [Fact]
        public async Task GetLeagueByIdAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var fixture = new Fixture();
            var league = fixture.Create<League>();
            baseService.GetDataEntityAsync<League>(Arg.Any<GetLeagueByIdCriteria>()).Returns(league);
            //Act
            var actual = await service.GetLeagueByIdAsync(league.LeagueId);
            //Assert
            Assert.Equal(league, actual);
        }

        [Fact]
        public void SearchLeague_WhenCalledMajorTrue_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            //Act
            var actual = service.SearchLeague(league, "Test1", 1);
            //Assert
            Assert.Equal(league[0], actual[0]);
        }


        [Fact]
        public void SearchLeague_WhenCalledMajorFalse_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            //Act
            var actual = service.SearchLeague(league, "Test1", 0);
            //Assert
            Assert.Equal(league[1], actual[0]);
        }

        [Fact]
        public void SortLeaguePriorityAscending_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();            
            //Act
            var actual = service.SortLeague(league, 1);
            //Assert
            Assert.Equal(league[0], actual[0]);
        }

        [Fact]
        public void SortLeaguePriorityDescending_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            //Act
            var actual = service.SortLeague(league, 2);
            //Assert
            Assert.Equal(league[1], actual[0]);
        }

        [Fact]
        public void SortLeagueCreatedDateDescending_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            //Act
            var actual = service.SortLeague(league, 3);
            //Assert
            Assert.Equal(league[1], actual[0]);
        }

        [Fact]
        public void SortLeagueCreatedDateAscending_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            //Act
            var actual = service.SortLeague(league, 4);
            //Assert
            Assert.Equal(league[0], actual[0]);
        }


        [Fact]
        public void GetLeaguePriority_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var fixture = new Fixture();
            var league = fixture.Create<IList<League>>();
            var service = GetService();
            //Act
            var actual = LeagueService.GetLeaguePriority(league);
            int priority = 0;
            foreach(var item in league)
            {
                if(item.Priority > priority)
                {
                    priority = item.Priority;
                }
            }
            //Assert
            Assert.Equal(priority + 1, actual);
        }

        private LeagueService GetService()
        {
            baseService = Substitute.For<IBaseService>();

            return new LeagueService(baseService);
        }
    }
}