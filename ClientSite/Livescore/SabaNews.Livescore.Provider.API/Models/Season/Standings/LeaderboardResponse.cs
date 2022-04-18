namespace SabaNews.Livescore.Provider.API.Models.Season
{
    public class LeaderboardResponse
    {
        public long? TeamId { get; set; }
        public short Position { get; set; }
        public short PositionHome { get; set; }
        public short PositionAway { get; set; }

        public short Total { get; set; }
        public short TotalHome { get; set; }
        public short TotalAway { get; set; }

        public short Won { get; set; }
        public short WonHome { get; set; }
        public short WonAway { get; set; }

        public short Loss { get; set; }
        public short LossHome { get; set; }
        public short LossAway { get; set; }

        public short Draw { get; set; }
        public short DrawHome { get; set; }
        public short DrawAway { get; set; }

        public int? PromotionId => Promotion?.Id;
        public PromotionRankingResponse Promotion { get; set; }

        public short Points { get; set; }
        public short PointsHome { get; set; }
        public short PointsAway { get; set; }
        public short DeductPoints { get; set; }

        public short Goals { get; set; }
        public short GoalsHome { get; set; }
        public short GoalsAway { get; set; }

        public short GoalsAgainst { get; set; }
        public short GoalsAgainstHome { get; set; }
        public short GoalsAgainstAway { get; set; }

        public short GoalDiff { get; set; }
        public short GoalDiffHome { get; set; }
        public short GoalDiffAway { get; set; }
    }
}