namespace SabaNews.Core.Constants.Livescore
{
    using SabaNews.Core.Models;
    using System.Collections.Generic;

    public static class Match
    {
        public const int Finished = 50; 
        public static readonly IReadOnlyList<MatchStatus> ListMatchStatus = new List<MatchStatus>
        {
            new MatchStatus { Id = 0, Name = "Not Started" },
            new MatchStatus { Id = 10, Name = "First Half" },
            new MatchStatus { Id = 20, Name = "Intermission" },
            new MatchStatus { Id = 30, Name = "Second Half" },
            new MatchStatus { Id = 40, Name = "ExtraTime" },
            new MatchStatus { Id = 41, Name = "Penalty Shootout" },
            new MatchStatus { Id = 50, Name = "Finished" },
            new MatchStatus { Id = 60, Name = "Delay" },
            new MatchStatus { Id = 61, Name = "Interrupt" },
            new MatchStatus { Id = 63, Name = "Postpone" },
            new MatchStatus { Id = 64, Name = "Cancel" },
            new MatchStatus { Id = 99, Name = "TBD" },
        };
    }
}
