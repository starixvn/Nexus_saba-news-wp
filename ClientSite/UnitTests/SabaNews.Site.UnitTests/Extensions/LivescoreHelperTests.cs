namespace SabaNews.Site.UnitTests.Extensions
{
    using AutoFixture;
    using Microsoft.AspNetCore.Mvc;
    using NSubstitute;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Constants.Livescore;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Site.Extensions;
    using System;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;

    public class LivescoreHelperTests
    {
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public void GetTeamName_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var team = fixture.Create<Team>();
            string teamName = !string.IsNullOrEmpty(team.TeamNameEN) ? team.TeamNameEN : team.TeamFullNameEN;
            teamName = !string.IsNullOrEmpty(team.TeamNameVN) ? team.TeamNameVN : teamName;
            //Act
            var result = LivescoreHelper.GetTeamName(team);

            // Assert
            Assert.Equal(result, teamName);
        }

        [Fact]
        public void GetStandingTeamName_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var standing = fixture.Create<SeasonStandings>();
            string teamName = !string.IsNullOrEmpty(standing.TeamNameEN) ? standing.TeamNameEN : standing.TeamFullNameEN;
            teamName = !string.IsNullOrEmpty(standing.TeamNameVN) ? standing.TeamNameVN : teamName;
            //Act
            var result = LivescoreHelper.GetStandingTeamName(standing);

            // Assert
            Assert.Equal(result, teamName);
        }

        [Fact]
        public void GetTopScorersPlayerName_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var topScorers = fixture.Create<TopScorers>();
            string teamName = string.IsNullOrEmpty(topScorers.PlayerNameEN) ? topScorers.PlayerFullNameEN : topScorers.PlayerNameEN;

            //Act
            var result = LivescoreHelper.GetTopScorersPlayerName(topScorers);

            // Assert
            Assert.Equal(result, teamName);
        }

        [Fact]
        public void GetTeamIcon_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var src = fixture.Create<string>();
            string expected = string.IsNullOrEmpty(src) ? "/css/images/logo-dummy.png" : src;

            //Act
            var result = LivescoreHelper.GetTeamIcon(src);

            // Assert
            Assert.Equal(result, expected);
        }

        [Fact]
        public void GetLeagueName_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var league = fixture.Create<League>();
            string leagueName = string.IsNullOrEmpty(league.LeagueNameEN) ? league.LeagueNameEN : league.LeagueFullNameEN;
            leagueName = !string.IsNullOrEmpty(league.LeagueNameVN) ? league.LeagueNameVN : leagueName;

            //Act
            var result = LivescoreHelper.GetLeagueName(league);

            // Assert
            Assert.Equal(result, leagueName);
        }


        [Fact]
        public void GroupStandingsByGroupName_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var seasonStandings = fixture.Create<IList<SeasonStandings>>();

            //Act
            var result = LivescoreHelper.GroupStandingsByGroupName(seasonStandings);

            // Assert
            Assert.Equal(result.Count, seasonStandings.Count);
        }

        [Fact]
        public void GetGroupNames_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var seasonStandings = fixture.Create<IList<SeasonStandings>>();
            var groupNames = new List<string>();
            foreach (var item in seasonStandings)
            {
                if (groupNames.Count == 0)
                {
                    groupNames.Add(item.GroupName);
                    continue;
                }

                var check = groupNames.Where(x => x == item.GroupName).ToList();
                if (check.Count == 0)
                {
                    groupNames.Add(item.GroupName);
                }
            }
            //Act
            var result = LivescoreHelper.GetGroupNames(seasonStandings);

            // Assert
            Assert.Equal(groupNames, result);
        }

        [Fact]
        public void IsLiveMatch_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var status = fixture.Create<int>();

            var live = MatchConstants.ListLiveMatchStatus.FirstOrDefault(item => item == status);

            //Act
            var result = LivescoreHelper.IsLiveMatch(status);

            // Assert
            Assert.Equal(result, live != 0);
        }

        [Fact]
        public void GetMatchTotalScore_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var match = fixture.Create<Match>();
            var isHome = fixture.Create<bool>();
            short expected;
            if (isHome)
            {
                expected = match.HomeTeamExtraScore > 0 ? match.HomeTeamExtraScore : match.HomeTeamTotalScore;
            }
            else
            {
                expected = match.AwayTeamExtraScore > 0 ? match.AwayTeamExtraScore : match.AwayTeamTotalScore;
            }

            //Act
            var result = LivescoreHelper.GetMatchTotalScore(match, isHome);

            // Assert
            Assert.Equal(result, expected);
        }

        [Fact]
        public void CheckMatchResult_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var homeScore = fixture.Create<short>();
            var awayScore = fixture.Create<short>();
            short expected;
            if (homeScore > awayScore)
            {
                expected = MatchConstants.Win;
            }
            else if (homeScore < awayScore)
            {
                expected = MatchConstants.Lose;
            }
            else
            {
                expected = MatchConstants.Draw;
            }

            //Act
            var result = LivescoreHelper.CheckMatchResult(homeScore, awayScore);

            // Assert
            Assert.Equal(result, expected);
        }

        [Fact]
        public void GetMatchResult_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var match = fixture.Create<Match>();
            var teamId = fixture.Create<long>();

            var homePEN = match.HomeTeamPenaltyScore;
            var awayPEN = match.AwayTeamPenaltyScore;
            var homeAGG = match.HomeTeamAggregateScore;
            var awayAGG = match.AwayTeamAggregateScore;
            var homeExtra = match.HomeTeamExtraScore;
            var awayExtra = match.AwayTeamExtraScore;
            var homeTotal = match.HomeTeamTotalScore;
            var awayTotal = match.AwayTeamTotalScore;
            if (match.AwayTeam.TeamId == teamId)
            {
                awayPEN = match.HomeTeamPenaltyScore;
                homePEN = match.AwayTeamPenaltyScore;
                awayAGG = match.HomeTeamAggregateScore;
                homeAGG = match.AwayTeamAggregateScore;
                awayExtra = match.HomeTeamExtraScore;
                homeExtra = match.AwayTeamExtraScore;
                awayTotal = match.HomeTeamTotalScore;
                homeTotal = match.AwayTeamTotalScore;
            }
            short expected;
            if (homePEN > 0 || awayPEN > 0)
            {
                expected = LivescoreHelper.CheckMatchResult(homePEN, awayPEN);
            }
            else if (match.IsSecondLeg && (homeAGG > 0 || awayAGG > 0))
            {
                expected = LivescoreHelper.CheckMatchResult(homeAGG, awayAGG);
            }
            else if (homeExtra > 0 || awayExtra > 0)
            {
                expected = LivescoreHelper.CheckMatchResult(homeExtra, awayExtra);
            }
            else
            {
                expected = LivescoreHelper.CheckMatchResult(homeTotal, awayTotal);
            }

            //Act
            var result = LivescoreHelper.GetMatchResult(match, teamId);

            // Assert
            Assert.Equal(result, expected);
        }
    }
}
