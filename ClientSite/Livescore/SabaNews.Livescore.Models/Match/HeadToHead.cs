namespace SabaNews.Livescore.Models.Match
{
    using System.Collections.Generic;

    public class HeadToHead
    {
        public IEnumerable<MatchHeadToHead> Common { get; set; }

        public IEnumerable<MatchHeadToHead> Home { get; set; }

        public IEnumerable<MatchHeadToHead> Away { get; set; }
    }
}
