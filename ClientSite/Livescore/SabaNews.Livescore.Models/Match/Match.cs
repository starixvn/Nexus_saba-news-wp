namespace SabaNews.Livescore.Models.Match
{
    using System;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Team;

    public class Match
    {
        public long MatchId { get; set; }

        public DateTime StartTime { get; set; }
        public DateTime EndTime { get; set; }

        public long LeagueId { get; set; }
        public League League { get; set; }

        public Team HomeTeam { get; set; }

        public Team AwayTeam { get; set; }

        public short Status { get; set; }

        public short HomeTeamHalfScore { get; set; }
        public short AwayTeamHalfScore { get; set; }

        public short HomeTeamTotalScore { get; set; }
        public short AwayTeamTotalScore { get; set; }

        public short HomeTeamAggregateScore { get; set; }
        public short AwayTeamAggregateScore { get; set; }
    }
}