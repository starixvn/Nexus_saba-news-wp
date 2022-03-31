namespace SabaNews.Models.LiveScore
{
    using System.Collections.Generic;

    public class SoccerMatchLineupDetail
    {
        public SoccerMatchLineup GeneralInfo { get; set; }

        public IEnumerable<SoccerMatchLineupTeam> Home { get; set; }

        public IEnumerable<SoccerMatchLineupTeam> Away { get; set; }


    }
}
