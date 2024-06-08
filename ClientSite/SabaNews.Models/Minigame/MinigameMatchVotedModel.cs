namespace SabaNews.Models.Event.Minigame
{
    using System;

    public class MinigameMatchVotedModel
    {
        public long MatchId { get; set; }

        public DateTime StartTime { get; set; }

        public byte Status { get; set; }

        public long LeagueId { get; set; }

        public long SeasonId { get; set;}
    }
}
