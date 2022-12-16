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

        [Fact]
        public void GetMatchNamePredict_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var match = fixture.Create<Match>();
            var votes = fixture.Create<IList<UserMinigameModel>>();

            var vote = votes.Where(x => x.MatchId == match.MatchId).ToList();
            string teamName = "";
            if (vote.Count == 0)
            {
                teamName = string.Empty;
            }
            else if (vote[0].TeamId == 0)
            {
                teamName = "Hòa";
            }
            else
            {
                teamName = match.HomeTeam.TeamId == vote[0].TeamId ? LivescoreHelper.GetTeamName(match.HomeTeam) : LivescoreHelper.GetTeamName(match.AwayTeam);
            }

            //Act
            var result = WorldCupHelper.GetMatchNamePredict(match, votes);

            // Assert
            Assert.Equal(result, teamName);
        }

        [Fact]
        public void GetVotePredict_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var match = fixture.Create<Match>();
            var votes = fixture.Create<IList<UserMinigameModel>>();

            var vote = votes.Where(x => x.MatchId == match.MatchId).ToList();
            //Act
            var result = WorldCupHelper.GetVotePredict(match, votes);

            // Assert
            Assert.Equal(result, vote.Count == 0 ? null : vote[0]);
        }

        [Fact]
        public void GetResultPredict_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var match = fixture.Create<Match>();
            var votes = fixture.Create<IList<UserMinigameModel>>();

            var vote = votes.Where(x => x.MatchId == match.MatchId).ToList();
            string iconResult = "";
            const int FinishStatus = 50;

            if (vote.Count == 0 && match.Status == FinishStatus)
            {
                iconResult = "emtry";
            }
            if (vote.Count != 0 && match.Status == FinishStatus)
            {
                var (status, score) = WorldCupHelper.GetVoteResult(match, vote[0]);
                iconResult = status ? "win" : "lose";
            }
            //Act
            var result = WorldCupHelper.GetResultPredict(match, votes);

            // Assert
            Assert.Equal(result, iconResult);
        }

        [Fact]
        public void GetMatch_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var vote = fixture.Create<UserMinigameModel>();

            Match match = null;
            foreach (var item in matches)
            {
                if (item.MatchId == vote.MatchId)
                {
                    match = item;
                }
            }
            //Act
            var result = WorldCupHelper.GetMatch(matches, vote);

            // Assert
            Assert.Equal(result, match);
        }

        [Fact]
        public void GetWinScore_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var stageId = fixture.Create<long>();

            var expected = stageId switch
            {
                WorldCup2022.FinalStage => WorldCup2022.MinigameScoreFinal,
                WorldCup2022.SemifinalsStage => WorldCup2022.MinigameScoreSemifinal,
                WorldCup2022.QuarterfinalsStage => WorldCup2022.MinigameScoreQuarterfinal,
                _ => WorldCup2022.MinigameScoreOthers,
            };

            //Act
            var result = WorldCupHelper.GetWinScore(stageId);

            // Assert
            Assert.Equal(result, expected);
        }

        [Fact]
        public void GetVoteResult_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var match = fixture.Create<Match>();
            var vote = fixture.Create<UserMinigameModel>();

            const int MatchStatusFinished = 50;
            const int MatchResultWin = 1;
            const int MatchResultDraw = 0;

            var status = false;
            var score = 0;
            if (match == null)
            {
                status = false;
                score = 0;
            }
            else if (match.Status != MatchStatusFinished)
            {
                status = false;
                score = 0;
            }
            else
            {
                var voteTeam = vote.TeamId == 0 ? match.HomeTeam.TeamId : vote.TeamId;
                var matchResult = LivescoreHelper.GetMatchResult(match, voteTeam);
                if (matchResult == MatchResultWin && vote.TeamId != 0)
                {
                    score = WorldCupHelper.GetWinScore(match.Stage.StageId);
                    status = true;
                }
                else if (matchResult == MatchResultDraw && vote.TeamId == 0)
                {
                    score = WorldCupHelper.GetWinScore(match.Stage.StageId);
                    status = true;
                }
            }

            //Act
            var result = WorldCupHelper.GetVoteResult(match, vote);

            // Assert
            Assert.Equal(result.Item1, status);
            Assert.Equal(result.Item2, score);
        }

        [Fact]
        public void GetUsersVote_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var users = fixture.Create<List<UserMinigameModel>>();
            var vote = fixture.Create<UserMinigameModel>();

            var expected = users;
            if (expected.Count == 0)
            {
                expected.Add(vote);
            }
            else
            {
                var count = expected.Where(x => x.UserId == vote.UserId).ToList().Count;
                if (count == 0)
                {
                    expected.Add(vote);
                }
            }

            //Act
            var result = WorldCupHelper.GetUsersVote(users, vote);

            // Assert
            Assert.Equal(result, expected);
        }

        [Fact]
        public void HandleStandings_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var users = fixture.Create<IList<UserMinigameModel>>();
            var votes = fixture.Create<IList<UserMinigameModel>>();
            var points = fixture.Create<List<int>>();

            //Act
            var result = WorldCupHelper.HandleStandings(users, votes, points);

            // Assert
            Assert.Equal(result.Count, users.Count);
        }

        [Fact]
        public void SortPlayersTime_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var standing = fixture.Create<List<UserMinigameModel>>();

            var sortPoint = new List<UserMinigameModel>();
            var sortPointZero = new List<UserMinigameModel>();
            foreach (var item in standing)
            {
                if (sortPointZero.Count == 0)
                {
                    sortPointZero.Add(item);
                }
                else
                {
                    var count = sortPointZero.Where(x => x.Point == item.Point).ToList().Count;
                    if (count > 0)
                    {
                        sortPointZero.Add(item);
                    }
                    if (count == 0 || item == standing[standing.Count - 1])
                    {
                        sortPointZero = sortPointZero.OrderBy(x => x.UpdatedDate).ToList();
                        sortPoint.AddRange(sortPointZero);
                        sortPointZero = new List<UserMinigameModel> { item };
                    }
                }
            }
            //Act
            var result = WorldCupHelper.SortPlayersTime(standing);

            // Assert
            Assert.Equal(result, sortPoint);
        }

        [Fact]
        public void CalculatorPointer_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var votes = fixture.Create<IList<UserMinigameModel>>();

            var points = new List<int>();
            var users = new List<UserMinigameModel>();
            foreach (var vote in votes)
            {
                var matchVote = WorldCupHelper.GetMatch(matches, vote);
                var (status, score) = WorldCupHelper.GetVoteResult(matchVote, vote);
                points.Add(status ? score : 0);
                users = WorldCupHelper.GetUsersVote(users, vote);
            }
            var standing = WorldCupHelper.HandleStandings(users, votes, points);
            //Act
            var result = WorldCupHelper.CalculatorPointer(matches, votes);

            // Assert
            Assert.Equal(result.Count, standing.Count);
        }

        [Fact]
        public void GetCountVotesStage_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var numStage = fixture.Create<int>();
            var votes = fixture.Create<IList<UserMinigameModel>>();

            int num = 0;
            foreach (UserMinigameModel vote in votes)
            {
                if (vote.Stage == numStage)
                {
                    num++;
                }
            }
            //Act
            var result = WorldCupHelper.GetCountVotesStage(votes, numStage);

            // Assert
            Assert.Equal(result, num);
        }

        [Fact]
        public void CheckActive_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var status = fixture.Create<string>();
            bool expected = true;
            if (status == WorldCup2022.LockStage || status == WorldCup2022.OnEndStage)
            {
                expected = false;
            }
            //Act
            var result = WorldCupHelper.CheckActive(status);

            // Assert
            Assert.Equal(result, expected);
        }

        [Fact]
        public void GetIconStage_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var status = fixture.Create<string>();
            string iconStage;
            if (status == WorldCup2022.LockStage || status == WorldCup2022.BeforeStartedStage)
            {
                iconStage = WorldCup2022.IconLock;
            }
            else if (status == WorldCup2022.OnEndStage)
            {
                iconStage = WorldCup2022.IconWinner;
            }
            else if (status == WorldCup2022.OnStartedStage)
            {
                iconStage = WorldCup2022.IconStanding;
            }
            else
            {
                iconStage = string.Empty;
            }
            //Act
            var result = WorldCupHelper.GetIconStage(status);

            // Assert
            Assert.Equal(result, iconStage);
        }

        [Fact]
        public void GetCountStageActiveMinigame_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            while(matches.Count < 64)
            {
                matches.Add(fixture.Create<Match>());
            }
            int result = 0;
            const int maxCountStage = 4;
            for (int i = 0; i < maxCountStage; i++)
            {
                var statusStage = WorldCupHelper.GetStatusStage(matches, i + 1);
                if (statusStage != WorldCup2022.LockStage && statusStage != WorldCup2022.BeforeStartedStage)
                {
                    result = i + 1;
                }
            }
            //Act
            var actual = WorldCupHelper.GetCountStageActiveMinigame(matches);

            // Assert
            Assert.Equal(result, actual);
        }

        [Fact]
        public void GetCountStageEndMinigame_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            while (matches.Count < 64)
            {
                matches.Add(fixture.Create<Match>());
            }
            int result = 0;
            const int MaxCountStage = 4;
            const int FinishStatus = 50;
            List<int> indexMatches = new() { 15, 31, 47, 63 };
            for (int i = 0; i < MaxCountStage; i++)
            {
                if (matches[indexMatches[i]].Status == FinishStatus)
                {
                    result = i + 1;
                }
            }
            //Act
            var actual = WorldCupHelper.GetCountStageEndMinigame(matches);

            // Assert
            Assert.Equal(result, actual);
        }

        [Fact]
        public void GetCountDownStage_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var status = fixture.Create<string>();
            var matches = fixture.Create<IList<Match>>();
            var numStage = 2;
            while (matches.Count < 64)
            {
                matches.Add(fixture.Create<Match>());
            }
            List<int> indexMatches = new() { 15, 31, 47, 63 };
            const int SubtractMinutes = -30;
            List<string> startStages = new() {
                WorldCup2022.TimestampMinigameStage01Start,
                WorldCup2022.TimestampMinigameStage02Start,
                WorldCup2022.TimestampMinigameStage03Start,
                WorldCup2022.TimestampMinigameStage04Start };
            string statusStage = status.Contains(WorldCup2022.BeforeStartedStage) ?
                startStages[numStage] :
                matches[indexMatches[numStage]].StartTime.AddHours(7).AddMinutes(SubtractMinutes).ToString(CultureInfo.InvariantCulture);
            //Act

            var result = WorldCupHelper.GetCountDownStage(status, matches, numStage);

            // Assert
            Assert.Equal(result, statusStage);
        }

        [Fact]
        public void GetStatusStage_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var matches = fixture.Create<IList<Match>>();
            var numStage = 2;
            while (matches.Count < 64)
            {
                matches.Add(fixture.Create<Match>());
            }

            List<int> indexMatches = new() { 15, 31, 47, 63 };
            const int ThirtyMinsToSecond = 1800;
            const int SixtyMinsToSecond = 3600;
            const int NinetyMinsToSecond = 5400;
            List<string> startStages = new() {
                WorldCup2022.TimestampMinigameStage01Start,
                WorldCup2022.TimestampMinigameStage02Start,
                WorldCup2022.TimestampMinigameStage03Start,
                WorldCup2022.TimestampMinigameStage04Start };

            var timeSpanNow = (DateTime.UtcNow.AddHours(7) - new DateTime(1970, 1, 1, 0, 0, 0));
            var timeStartStage = DateTime.Parse(startStages[numStage - 1]) - new DateTime(1970, 1, 1, 0, 0, 0);
            var checkNow = timeStartStage.TotalSeconds - timeSpanNow.TotalSeconds;
            string statusStage;
            if (checkNow > 0)
            {
                statusStage = checkNow > SixtyMinsToSecond ? WorldCup2022.LockStage : WorldCup2022.BeforeStartedStage;
            }
            else
            {
                var timeSpanEnd = (matches[indexMatches[numStage - 1]].StartTime.AddHours(7) - new DateTime(1970, 1, 1, 0, 0, 0));
                var checkNowEnd = timeSpanEnd.TotalSeconds - timeSpanNow.TotalSeconds;
                if (checkNowEnd > NinetyMinsToSecond)
                {
                    statusStage = WorldCup2022.OnStartedStage;
                }
                else if (checkNowEnd <= NinetyMinsToSecond && checkNowEnd > ThirtyMinsToSecond)
                {
                    statusStage = WorldCup2022.BeforeEndStage;
                }
                else { statusStage = WorldCup2022.OnEndStage; }
            }
            //Act

            var result = WorldCupHelper.GetStatusStage(matches, numStage);

            // Assert
            Assert.Equal(result, statusStage);
        }

        [Fact]
        public void CheckStartStage_WhenCalled_ReturnExpectedData()
        {
            // Arrange
            var numStage = 2;
            List<string> startStages = new() {
                WorldCup2022.TimestampMinigameStage01Start,
                WorldCup2022.TimestampMinigameStage02Start,
                WorldCup2022.TimestampMinigameStage03Start,
                WorldCup2022.TimestampMinigameStage04Start };

            var timeSpanNow = (DateTime.UtcNow.AddHours(7) - new DateTime(1970, 1, 1, 0, 0, 0));
            var timeStartStage = DateTime.Parse(startStages[numStage - 1]) - new DateTime(1970, 1, 1, 0, 0, 0);
            var checkNow = timeStartStage.TotalSeconds - timeSpanNow.TotalSeconds;
            bool expected = checkNow <= 0;
            //Act

            var result = WorldCupHelper.CheckStartStage(numStage);

            // Assert
            Assert.Equal(result, expected);
        }
    }
}
