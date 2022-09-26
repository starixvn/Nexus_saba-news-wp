namespace SabaNews.Core.Constants
{
    using SabaNews.Core.Models;
    using System.Collections.Generic;

    public static class Livescore
    {
        public static readonly IReadOnlyList<MatchStatus> ListMatchStatus = new List<MatchStatus>
        {
            new MatchStatus { Id = 0, Name = "NotStarted" },
            new MatchStatus { Id = 10, Name = "FirstHalf" },
            new MatchStatus { Id = 20, Name = "Intermission" },
            new MatchStatus { Id = 30, Name = "SecondHalf" },
            new MatchStatus { Id = 40, Name = "ExtraTime" },
            new MatchStatus { Id = 41, Name = "PenaltyShootout" },
            new MatchStatus { Id = 50, Name = "Finished" },
            new MatchStatus { Id = 60, Name = "Delay" },
            new MatchStatus { Id = 61, Name = "Interrupt" },
            new MatchStatus { Id = 62, Name = "CutInHalf" },
            new MatchStatus { Id = 63, Name = "Postpone" },
            new MatchStatus { Id = 64, Name = "Cancel" },
            new MatchStatus { Id = 99, Name = "TBD" },
        };
    }
}
