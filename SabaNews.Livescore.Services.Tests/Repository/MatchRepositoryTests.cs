namespace SabaNews.Livescore.Services.Tests.Repository
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using AutoFixture;
    using Fanex.Data;
    using Fanex.Data.Repository;
    using NSubstitute;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Livescore.Repositories;
    using Xunit;

    public class MatchRepositoryTests
    {
        private readonly IObjectDb objectDb;
        private readonly Fixture fixture;

        public MatchRepositoryTests()
        {
            DbSettingProviderManager
            .StartTestingSession()
            .Run();
            fixture = new Fixture();
            objectDb = Substitute.For<IObjectDb>();
            ObjectDbFactory.Mock((key, time) => objectDb);
        }

        [Fact]
        public async Task GetMatchByDateAsync_WhenCalled_ReturnExpectedResult()
        {
            var criteria = FakeCriteria();
            var repository = new MatchRepository();

            var matches = fixture.Create<IList<Match>>();

            objectDb.QueryAsync(Arg.Any<Func<Match, League, Season, SeasonStage, Team, Team, Match>>(),
                splitOn: Arg.Is("LeagueId, SeasonId, StageId, HomeTeamId, AwayTeamId"),
                Arg.Is(criteria)
                ).Returns(matches);
            //Actual
            var actual = await repository.GetMatchByDateAsync(criteria);

            //Assert

            Assert.Equal(matches, actual);
        }

        [Fact]
        public async Task GetMatchByIdsAsync_WhenCalled_ReturnExpectedResult()
        {
            var criteria = FakeCriteria();
            var repository = new MatchRepository();

            var matches = fixture.Create<IList<Match>>();

            objectDb.QueryAsync(Arg.Any<Func<Match, League, Team, Team, Match>>(),
                splitOn: Arg.Is("LeagueId, HomeTeamId, AwayTeamId"),
                Arg.Is(criteria)
                ).Returns(matches);
            //Actual
            var actual = await repository.GetMatchByIdsAsync(criteria);

            //Assert

            Assert.Equal(matches, actual);
        }

        [Fact]
        public async Task GetMatchByIdAsync_WhenCalled_ReturnExpectedResult()
        {
            var criteria = FakeCriteria();
            var repository = new MatchRepository();

            var match = fixture.Create<Match>();

            objectDb.QueryEntityAsync(Arg.Any<Func<Match, League, Team, Team, Match>>(),
                splitOn: Arg.Is("LeagueId, HomeTeamId, AwayTeamId"),
                Arg.Is(criteria)
                ).Returns(match);

            //Actual
            var actual = await repository.GetMatchByIdAsync(criteria);

            //Assert

            Assert.Equal(match, actual);
        }

        [Fact]
        public async Task GetHeadToHeadAsync_WhenNoMatchReturn_ReturnExpectedEmptyList()
        {
            var repo = new MatchRepository();

            var multi = FakeMultipleResultReader();
            var criteria = FakeCriteria();

            var matches = fixture.Create<IEnumerable<Match>>();
            var team = new H2HTeam { HomeId = matches.FirstOrDefault().HomeTeam.TeamId, AwayId = matches.FirstOrDefault().AwayTeam.TeamId };

            var expected = new List<Match> { matches.FirstOrDefault() };
            multi.Read(Arg.Any<Func<Match, League, Team, Team, Match>>(),
                Arg.Any<string>())
                .Returns(matches);

            multi.ReadFirstOrDefaultAsync<H2HTeam>().Returns(team);

            objectDb.QueryMultipleAsync(criteria).Returns(multi);

            var actual = await repo.GetHeadToHeadAsync(criteria);
            Assert.Single(actual.Common);
            await objectDb.Received(1).QueryMultipleAsync(Arg.Any<ICriteria>());
        }

        private ICriteria FakeCriteria()
        {
            var criteria = Substitute.For<ICriteria>();
            criteria.GetSettingKey().Returns("Livescore_Tests");
            criteria.IsValid().Returns(true);

            return criteria;
        }

        private IMultiResultReader FakeMultipleResultReader()
        {
            return Substitute.For<IMultiResultReader>();
        }
    }
}