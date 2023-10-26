namespace SabaNews.Livescore.SyncData.Models.Matches
{
    public class MatchTeamStatistic
    {
        public bool IsHomeTeam { get; set; }
        public long MatchId { get; set; }
        public int? Goal { get; set; }
        public int? CornerKick { get; set; }
        public int? YellowCard { get; set; }
        public int? RedCard { get; set; }
        public int? OutBall { get; set; }
        public int? FreeKick { get; set; }
        public int? GoalKick { get; set; }
        public int? PenaltyKick { get; set; }
        public int? YellowToRed { get; set; }
        public int? FailPenaltyKick { get; set; }
        public int? OwnGoal { get; set; }
        public int? InjuryTime { get; set; }
        public int? OnGoal { get; set; }
        public int? MissGoal { get; set; }
        public int? Attack { get; set; }
        public int? DangerAttack { get; set; }
        public int? ControlRate { get; set; }
        public int? ExtraScore { get; set; }
        public int? PenaltyScore { get; set; }
        public int? Assists { get; set; }
        public int? First { get; set; }
        public int? Follow { get; set; }
        public int? Foul { get; set; }
        public string Grade { get; set; }
        public int? HalfCorner { get; set; }
        public string HalfPossession { get; set; }
        public int? Head { get; set; }
        public int? HeadSuccess { get; set; }
        public int? Intercept { get; set; }
        public int? OffSide { get; set; }
        public int? OnDoor { get; set; }
        public int? Pass { get; set; }
        public string PassSuccess { get; set; }
        public int? Past { get; set; }
        public int? Shoot { get; set; }
        public int? ShootOut { get; set; }
        public int? ShootStop { get; set; }
        public int? Slide { get; set; }
        public int? Steals { get; set; }
    }
}