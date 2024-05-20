namespace SabaNews.Livescore.Services.Tests.Services
{
    using AutoFixture;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Livescore.Services.Criteria.League;
    using SabaNews.Livescore.Services.Criteria.Team;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class TeamServiceTests
    {
        private IBaseService baseService;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task GetTeamByStage_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            

            var seasonId = fixture.Create<long>();
            var stageId = fixture.Create<long>();
            var criteria = fixture.Create<GetTeamByStageCriteria>();

            var teams = fixture.Create<IList<TeamStage>>();
            baseService.GetDataAsync<TeamStage>(Arg.Any<GetTeamByStageCriteria>()).Returns(teams);
            //Act
            var actual = await service.GetTeamByStage(seasonId, stageId);
            //Assert
            Assert.Equal(teams, actual);
        }

        [Fact]
        public async Task GetTeamPlayerById_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            

            var teamId = fixture.Create<long>();
            var criteria = fixture.Create<GetTeamByStageCriteria>();

            var teams = fixture.Create<IList<PlayerCustom>>();
            baseService.GetDataAsync<PlayerCustom>(Arg.Any<GetPlayerCustomByTeamIdCriteria>()).Returns(teams);
            //Act
            var actual = await service.GetTeamPlayerById(teamId);
            //Assert
            Assert.Equal(teams, actual);
        }

        [Fact]
        public async Task GetPlayerById_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            

            var playerId = fixture.Create<long>();
            var criteria = fixture.Create<GetTeamByStageCriteria>();

            var player = fixture.Create<PlayerCustom>();
            baseService.GetDataEntityAsync<PlayerCustom>(Arg.Any<GetPlayerCustomByIdCriteria>()).Returns(player);
            //Act
            var actual = await service.GetPlayerById(playerId);
            //Assert
            Assert.Equal(player, actual);
        }

        [Fact]
        public async Task CreatePlayerAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var cmd = fixture.Create<CreateTeamPlayerCommand>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<CreateTeamPlayerCommand>(x => cmd = x)).Returns(expected);
            //Act
            var actual = await service.CreatePlayerAsync(cmd);
            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task UpdatePlayerAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var cmd = fixture.Create<UpdateTeamPlayerCommand>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateTeamPlayerCommand>(x => cmd = x)).Returns(expected);
            //Act
            var actual = await service.UpdatePlayerAsync(cmd);
            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task DeletePlayerAsync_WhenCalled_ReturnExpectedResult()
        {
            //Arrange
            var service = GetService();
            var playerId = fixture.Create<long>();
            var cmd = fixture.Create<DeleteTeamPlayerCommand>();
            var expected = new CommandResult { Code = 0 };
            baseService.ExecuteNonQueryAsync(Arg.Do<DeleteTeamPlayerCommand>(x => cmd = x)).Returns(expected);
            //Act
            var actual = await service.DeletePlayerAsync(playerId);
            //Assert
            Assert.Equal(expected, actual);
        }



        private TeamService GetService()
        {
            baseService = Substitute.For<IBaseService>();

            return new TeamService(baseService);
        }
    }
}