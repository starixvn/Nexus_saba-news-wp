namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using Newtonsoft.Json;

    public class TeamStatisticResponse
    {
        public long TeamId { get; set; }

        [JsonProperty("NameEn")]
        public string Name { get; set; }

        public string Icon { get; set; }
        public int YellowCard { get; set; }
        public int RedCard { get; set; }
        public int FullScore { get; set; }
        public int HalfScore { get; set; }
        public int CornerKick { get; set; }
        public int ExtraScore { get; set; }
        public int PenaltiesScore { get; set; }
        public int OutBall { get; set; }
        public int FreeKick { get; set; }
        public int GoalKick { get; set; }
        public int PenaltyKick { get; set; }
        public int YellowToRed { get; set; }
        public int FailPenaltyKick { get; set; }
        public int OwnGoal { get; set; }
        public int InjuryTime { get; set; }
        public int OnGoal { get; set; }
        public int MissGoal { get; set; }
        public int Attack { get; set; }
        public int DangerAttack { get; set; }
        public int ControlRate { get; set; }
    }
}