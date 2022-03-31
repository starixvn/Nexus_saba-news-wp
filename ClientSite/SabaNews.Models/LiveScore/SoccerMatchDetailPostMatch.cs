namespace SabaNews.Models.LiveScore
{
    using System.Collections.Generic;

    public class SoccerMatchDetailPostMatch
    {
        public IEnumerable<SoccerMatchCommentaryEvent> Incidents { get; set; }

        public SoccerMatchTeamStatistic HomeStatistic { get; set; }

        public SoccerMatchTeamStatistic AwayStatistic { get; set; }
    }
}
