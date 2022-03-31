using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Models.LiveScore
{
    public class SoccerMatchLineup
    {
        public int MatchId { get; set; }
        public string HomeFormation { get; set; }
        public string AwayFormation { get; set; }
        public string HomeCoach { get; set; }
        public string AwayCoach { get; set; }
    }
}
