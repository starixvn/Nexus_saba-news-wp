namespace SabaNews.Site.Extensions
{
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;

    public static class LivescoreHelper
    {
        public static string GetTeamName(Team team)
        {
            string teamName = string.IsNullOrEmpty(team.TeamNameEN) ? team.TeamNameEN : team.TeamFullNameEN;
            teamName = !string.IsNullOrEmpty(team.TeamNameVN) ? team.TeamNameVN : teamName;
            return teamName;
        }

        public static string GetStandingTeamName(SeasonStandings standing)
        {
            string teamName = string.IsNullOrEmpty(standing.TeamNameEN) ? standing.TeamNameEN : standing.TeamFullNameEN;
            teamName = !string.IsNullOrEmpty(standing.TeamNameVN) ? standing.TeamNameVN : teamName;
            return teamName;
        }

        public static string GetTeamIcon(string src)
        {
            return string.IsNullOrEmpty(src) ? "css/images/logo-dummy.png" : src;
        }
    }
}
