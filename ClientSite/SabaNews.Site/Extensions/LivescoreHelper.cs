namespace SabaNews.Site.Extensions
{
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;

    public static class LivescoreHelper
    {
        public static string GetTeamName(Team team)
        {
            string teamName;
            if (!string.IsNullOrEmpty(team.TeamNameVN))
            {
                teamName = team.TeamNameVN;
            }
            else
            {
                teamName = string.IsNullOrEmpty(team.TeamNameEN) ? team.TeamNameEN : team.TeamFullNameEN;
            }
            return teamName;
        }

        public static string GetStandingTeamName(SeasonStandings standing)
        {
            string teamName;
            if (!string.IsNullOrEmpty(standing.TeamNameVN))
            {
                teamName = standing.TeamNameVN;
            }
            else
            { 
                teamName = string.IsNullOrEmpty(standing.TeamNameEN) ? standing.TeamNameEN : standing.TeamFullNameEN;
            }
            return teamName;
        }

        public static string GetTeamIcon(string src)
        {
            return string.IsNullOrEmpty(src) ? "css/images/logo-dummy.png" : src;
        }
    }
}
