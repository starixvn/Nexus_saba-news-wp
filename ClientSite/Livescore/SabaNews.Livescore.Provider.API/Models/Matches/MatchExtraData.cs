namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using Newtonsoft.Json;

    public class MatchExtraData
    {
        public int?[] Goal { get; set; }

        [JsonProperty("cKick")]
        public int?[] CornerKick { get; set; }

        [JsonProperty("yCard")]
        public int?[] YellowCard { get; set; }

        [JsonProperty("rCard")]
        public int?[] RedCard { get; set; }

        public int?[] OutBall { get; set; }

        [JsonProperty("fKick")]
        public int?[] FreeKick { get; set; }

        [JsonProperty("gKick")]
        public int?[] GoalKick { get; set; }

        [JsonProperty("pKick")]
        public int?[] PenaltyKick { get; set; }

        [JsonProperty("Surfaces")]
        public int?[] YellowToRed { get; set; }

        [JsonProperty("fpKick")]
        public int?[] FailedPenalty { get; set; }

        public int?[] OwnGoal { get; set; }
        public int?[] InjuryTime { get; set; }
        public int?[] OnGoal { get; set; }
        public int?[] MissGoal { get; set; }
        public int?[] Attack { get; set; }
        public int?[] DangerAttack { get; set; }
        public string[] ControlRate { get; set; }
        public int?[] ExtraScore { get; set; }

        [JsonProperty("pScore")]
        public int?[] PenaltyScore { get; set; }

        public int?[] Assists { get; set; }
        public int?[] First { get; set; }
        public int?[] Follow { get; set; }
        public int?[] Foul { get; set; }
        public string[] Grade { get; set; }
        public int?[] HalfCorner { get; set; }
        public string[] HalfPossession { get; set; }
        public int?[] Head { get; set; }
        public int?[] HeadSuccess { get; set; }
        public int?[] Intercept { get; set; }
        public int?[] OffSide { get; set; }
        public int?[] OnDoor { get; set; }
        public int?[] Pass { get; set; }
        public string[] PassSuccess { get; set; }
        public int?[] Past { get; set; }
        public int?[] Shoot { get; set; }
        public int?[] ShootOut { get; set; }
        public int?[] ShootStop { get; set; }
        public int?[] Slide { get; set; }
        public int?[] Steals { get; set; }
    }
}