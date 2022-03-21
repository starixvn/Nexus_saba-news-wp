namespace SabaNews.Models.LiveScore
{
    public class SoccerMatchTeam
    {
        public int TeamId { get; set; }
        public string NameCn { get; set; }
        public string NameEn { get; set; }
        public int YellowCard { get; set; }
        public int RedCard { get; set; }
        public int FullScore { get; set; }
        public int HalfScore { get; set; }
        public int CornerKick { get; set; }
        public int ExtractScore { get; set; }
        public int PenaltiesScore { get; set; }
        public int OutBall { get; set; }
        public int FreeKick { get; set; }
        public int GoalKick { get; set; }
        public int PenaltyKick { get; set; }
        public int YellowToRed { get; set; }
        public int FailPenaltyKick { get; set; }
        public int OwnGoal { get; set; }
        public int MissGoal { get; set; }
        public int Attack { get; set; }
        public int DangerAttack { get; set; }
        public int ControlRate { get; set; }
        public string Icon { get; set; }
    }
}