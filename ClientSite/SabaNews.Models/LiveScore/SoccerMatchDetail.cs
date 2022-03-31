namespace SabaNews.Models.LiveScore
{
    using System.Collections.Generic;

    public class SoccerMatchDetail
    {
        public SoccerMathGeneralInfo GeneralInfo { get; set; }
        public SoccerMatchTeamStatistic HomeTeam { get; set; }
        public SoccerMatchTeamStatistic AwayTeam { get; set; }
        public IEnumerable<SoccerMatchCommentaryEvent> Incidents { get; set; }
        public SoccerMatchLineupDetail Lineup { get; set; }
    }
}
