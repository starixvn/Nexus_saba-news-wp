namespace SabaNews.Site.Extensions
{
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
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
            string teamName = string.IsNullOrEmpty(standing.TeamNameEN) ? standing.TeamNameEN : standing.TeamFullNameEN;
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

        public static short GetMatchTotalScore(Match match, bool isHome)
        {
            if (isHome)
            {
                return match.HomeTeamExtraScore > 0 ? match.HomeTeamExtraScore : match.HomeTeamTotalScore;
            }
            return match.AwayTeamExtraScore > 0 ? match.AwayTeamExtraScore : match.AwayTeamTotalScore;
        }
 

    }
}
