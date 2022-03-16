using System.Collections.Generic;

namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    public class MatchResponseWrapper
    {
        public MatchResponse Match { get; set; }
    }

    public class MatchListResponseWrapper
    {
        public List<MatchResponse> Match { get; set; }
    }

    public class MatchResponse
    {
        public long MatchId { get; set; }

        public string Schedule { get; set; }

        public int? MatchTime { get; set; }

        public long LeagueId { get; set; }

        public string LeagueNameEn { get; set; }

        public bool IsTop { get; set; }

        public bool IsMatchEndTop { get; set; }

        public string EndTime { get; set; }

        public bool IsMain { get; set; }

        public bool IsFour { get; set; }

        public int? Status { get; set; }

        public TeamStatisticResponse HomeTeam { get; set; }

        public TeamStatisticResponse AwayTeam { get; set; }
    }
}