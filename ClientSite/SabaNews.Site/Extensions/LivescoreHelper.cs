namespace SabaNews.Site.Extensions
{
    using SabaNews.Core.Constants;
    using SabaNews.Core.Constants.Livescore;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Models.Event.WorldCup;
    using System;
    using System.Collections.Generic;
    using System.Linq;

    public static class LivescoreHelper
    {
        public static string GetTeamName(Team team)
        {
            string teamName = !string.IsNullOrEmpty(team.TeamNameEN) ? team.TeamNameEN : team.TeamFullNameEN;
            teamName = !string.IsNullOrEmpty(team.TeamNameVN) ? team.TeamNameVN : teamName;
            return teamName;
        }


        public static string GetStandingTeamName(SeasonStandings standing)
        {
            string teamName = !string.IsNullOrEmpty(standing.TeamNameEN) ? standing.TeamNameEN : standing.TeamFullNameEN;
            teamName = !string.IsNullOrEmpty(standing.TeamNameVN) ? standing.TeamNameVN : teamName;
            return teamName;
        }

        public static string GetTopScorersPlayerName(TopScorers topScorers)
        {
            string teamName = string.IsNullOrEmpty(topScorers.PlayerNameEN) ? topScorers.PlayerFullNameEN : topScorers.PlayerNameEN;
            return teamName;
        }

        public static string GetTeamIcon(string src)
        {
            return string.IsNullOrEmpty(src) ? "/css/images/logo-dummy.png" : src;
        }

        public static string GetLeagueName(League league)
        {
            string leagueName = string.IsNullOrEmpty(league.LeagueNameEN) ? league.LeagueNameEN : league.LeagueFullNameEN;
            leagueName = !string.IsNullOrEmpty(league.LeagueNameVN) ? league.LeagueNameVN : leagueName;
            return leagueName;
        }


        public static List<StandingGroupModel> GroupStandingsByGroupName(IList<SeasonStandings> seasonStandings)
        {
            var groupNames = GetGroupNames(seasonStandings);
            var standingsViewModels = new List<StandingGroupModel>();

            for (int i = 0; i < groupNames.Count; i++)
            {
                standingsViewModels.Add(new StandingGroupModel
                {
                    GroupName = groupNames[i],
                    Standings = seasonStandings.Where(x => x.GroupName == groupNames[i]).ToList()
                });
            }
            return standingsViewModels;
        }

        public static List<string> GetGroupNames(IList<SeasonStandings> seasonStandings)
        {
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
            return groupNames;
        }

        public static bool IsLiveMatch(int status)
        {
            var live = MatchConstants.ListLiveMatchStatus.FirstOrDefault(item => item == status);
            return live != 0;

        }

        public static short GetMatchTotalScore(Match match, bool isHome)
        {
            if (isHome)
            {
                return match.HomeTeamExtraScore > 0 ? match.HomeTeamExtraScore : match.HomeTeamTotalScore;
            }
            return match.AwayTeamExtraScore > 0 ? match.AwayTeamExtraScore : match.AwayTeamTotalScore;
        }

        public static short CheckMatchResult(short? homeScore, short? awayScore)
        {
            short result;
            if (homeScore > awayScore)
            {
                result = MatchConstants.Win;
            }
            else if (homeScore < awayScore)
            {
                result = MatchConstants.Lose;
            }
            else
            {
                result = MatchConstants.Draw;
            }
            return result;
        }

        public static short GetMatchResult(Match match, long teamId)
        {
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
            short result;
            if (homePEN > 0 || awayPEN > 0)
            {
                result = CheckMatchResult(homePEN, awayPEN);
            }
            else if (match.IsSecondLeg && (homeAGG > 0 || awayAGG > 0))
            {
                result = CheckMatchResult(homeAGG, awayAGG);
            }
            else if (homeExtra > 0 || awayExtra > 0)
            {
                result = CheckMatchResult(homeExtra, awayExtra);
            }
            else
            {
                result = CheckMatchResult(homeTotal, awayTotal);
            }
            return result;
        }

        public static int GetCountVotesStage(IList<UserMinigameModel> votes ,int numStage) 
        {
            int dem = 0;
            foreach (UserMinigameModel vote in votes)
            {
                if(vote.Stage == numStage)
                {
                    dem++;
                }
            }
            return dem;
        }

        public static string GetIconStage(string status)
        {
            string iconStage;
            if(status == WorldCup2022.LockStage)
            {
                iconStage = WorldCup2022.IconLock;
            }
            else if(status == WorldCup2022.OnEndStage)
            {
                iconStage = WorldCup2022.IconSearch;
            }
            else
            {
                iconStage = string.Empty;
            }
            return iconStage;
        }

        public static string GetCountDownStage(string status, IList<Match> matches, int numStage)
        {
            List<int> indexMatches = new() { 15, 31, 47, 63 };
            List<string> longs = new() {
                "11/24/2022, 6:30:00 PM",
                "11/25/2022, 4:00:00 PM",
                "11/29/2022, 9:00:00 PM",
                "12/3/2022, 9:00:00 PM" };
            string statusStage;
            if (status.Contains(WorldCup2022.BeforeStartedStage))
            {
                statusStage = longs[numStage];
            }
            else
            {
                statusStage = matches[indexMatches[numStage]].StartTime.AddMinutes(-30).ToString();
            }
            return statusStage;            
        }

        public static string GetStatusStage(IList<Match> matches, int numStage)
        {
            List<int> indexMatches = new() { 15, 31, 47, 63 };
            List<long> longs = new() { 
                WorldCup2022.TimestampMinigameStage01Start, 
                WorldCup2022.TimestampMinigameStage02Start,
                WorldCup2022.TimestampMinigameStage03Start, 
                WorldCup2022.TimestampMinigameStage04Start };

            var timeSpanNow = (DateTime.UtcNow - new DateTime(1970, 1, 1, 0, 0, 0));
            var checkNow = longs[numStage] - timeSpanNow.TotalSeconds;
            string statusStage;
            if (checkNow > 0)
            {
                if (checkNow > 3600)
                {
                    statusStage = WorldCup2022.LockStage;
                }
                else { statusStage = WorldCup2022.BeforeStartedStage; }
            }
            else
            {
                var timeSpanEnd = (matches[indexMatches[numStage]].StartTime - new DateTime(1970, 1, 1, 0, 0, 0));
                var checkNowEnd = timeSpanEnd.TotalSeconds - timeSpanNow.TotalSeconds;
                if (checkNowEnd > 5400)
                {
                    statusStage = WorldCup2022.OnStartedStage;
                }
                else if (checkNowEnd <= 5400 && checkNowEnd > 1800)
                {
                    statusStage = WorldCup2022.BeforeEndStage;
                }
                else { statusStage = WorldCup2022.OnEndStage; }
            }

            return statusStage;
        }

    }
}
