namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using Newtonsoft.Json;
    using SabaNews.Livescore.Provider.API.Models.Team;

    public class MatchExtraData
    {
        public int[] Goal { get; set; }

        [JsonProperty("cKick")]
        public int[] ConnerKick { get; set; }

        [JsonProperty("yCard")]
        public int[] YellowCard { get; set; }

        [JsonProperty("rCard")]
        public int[] RedCard { get; set; }

        public int[] OutBall { get; set; }

        [JsonProperty("fKick")]
        public int[] FreeKick { get; set; }

        [JsonProperty("gKick")]
        public int[] GoalKick { get; set; }

        [JsonProperty("pKick")]
        public int[] PenaltyKick { get; set; }

        [JsonProperty("Surfaces")]
        public int[] YellowToRed { get; set; }

        [JsonProperty("fpKick")]
        public int[] FailedPenalty { get; set; }

        public int[] OwnGoal { get; set; }
        public int[] InjuryTime { get; set; }
        public int[] OnGoal { get; set; }
        public int[] MissGoal { get; set; }
        public int[] Attck { get; set; }
        public int[] DangerAttack { get; set; }
        public int[] ControlRate { get; set; }
        public int[] ExtraScore { get; set; }

        [JsonProperty("pScore")]
        public int[] PenaltyShootoutScore { get; set; }

        public int[] Assists { get; set; }
        public int[] First { get; set; }
        public int[] Follow { get; set; }
        public int[] Foul { get; set; }
        public string[] Grade { get; set; }
        public int[] HalfConner { get; set; }
        public string[] HalfPossession { get; set; }
        public int[] Head { get; set; }
        public int[] HeadSuccess { get; set; }
        public int[] Intercept { get; set; }
        public int[] OffSide { get; set; }
        public int[] OnDoor { get; set; }
        public int[] Pass { get; set; }
        public int[] PassSuccess { get; set; }
        public int[] Past { get; set; }
        public int[] Shoot { get; set; }
        public int[] ShootOut { get; set; }
        public int[] ShootStop { get; set; }
        public int[] Slide { get; set; }
        public int[] Steals { get; set; }

        public TeamStatistic ToHomeTeamDetails() =>
            new()
            {
                Goal = Goal is not null ? Goal[0] : 0,
                ConnerKick = ConnerKick is not null ? ConnerKick[0] : 0,
                YellowCard = YellowCard is not null ? YellowCard[0] : 0,
                RedCard = RedCard is not null ? RedCard[0] : 0,
                OutBall = OutBall is not null ? OutBall[0] : 0,
                FreeKick = FreeKick is not null ? FreeKick[0] : 0,
                GoalKick = GoalKick is not null ? GoalKick[0] : 0,
                PenaltyKick = PenaltyKick is not null ? PenaltyKick[0] : 0,
                YellowToRed = YellowToRed is not null ? YellowToRed[0] : 0,
                FailedPenalty = FailedPenalty is not null ? FailedPenalty[0] : 0,
                OwnGoal = OwnGoal is not null ? OwnGoal[0] : 0,
                InjuryTime = InjuryTime is not null ? InjuryTime[0] : 0,
                OnGoal = OnGoal is not null ? OnGoal[0] : 0,
                MissGoal = MissGoal is not null ? MissGoal[0] : 0,
                Attck = Attck is not null ? Attck[0] : 0,
                DangerAttack = DangerAttack is not null ? DangerAttack[0] : 0,
                ControlRate = ControlRate is not null ? ControlRate[0] : 0,
                ExtraScore = ExtraScore is not null ? ExtraScore[0] : 0,
                PenaltyShootoutScore = PenaltyShootoutScore is not null ? PenaltyShootoutScore[0] : 0,
                Assists = Assists is not null ? Assists[0] : 0,
                First = First is not null ? First[0] : 0,
                Follow = Follow is not null ? Follow[0] : 0,
                Foul = Foul is not null ? Foul[0] : 0,
                Grade = Grade.Length > 0 ? Grade[0] : string.Empty,
                HalfConner = HalfConner is not null ? HalfConner[0] : 0,
                HalfPossession = HalfPossession.Length > 0 ? HalfPossession[0] : string.Empty,
                Head = Head is not null ? Head[0] : 0,
                HeadSuccess = HeadSuccess is not null ? HeadSuccess[0] : 0,
                Intercept = Intercept is not null ? Intercept[0] : 0,
                OffSide = OffSide is not null ? OffSide[0] : 0,
                OnDoor = OnDoor is not null ? OnDoor[0] : 0,
                Pass = Pass is not null ? Pass[0] : 0,
                PassSuccess = PassSuccess is not null ? PassSuccess[0] : 0,
                Past = Past is not null ? Past[0] : 0,
                Shoot = Shoot is not null ? Shoot[0] : 0,
                ShootOut = ShootOut is not null ? ShootOut[0] : 0,
                ShootStop = ShootStop is not null ? ShootStop[0] : 0,
                Slide = Slide is not null ? Slide[0] : 0,
                Steals = Steals is not null ? Steals[0] : 0
            };

        public TeamStatistic ToAwayTeamDetails() =>
            new()
            {
                Goal = Goal is not null ? Goal[1] : 0,
                ConnerKick = ConnerKick is not null ? ConnerKick[1] : 0,
                YellowCard = YellowCard is not null ? YellowCard[1] : 0,
                RedCard = RedCard is not null ? RedCard[1] : 0,
                OutBall = OutBall is not null ? OutBall[1] : 0,
                FreeKick = FreeKick is not null ? FreeKick[1] : 0,
                GoalKick = GoalKick is not null ? GoalKick[1] : 0,
                PenaltyKick = PenaltyKick is not null ? PenaltyKick[1] : 0,
                YellowToRed = YellowToRed is not null ? YellowToRed[1] : 0,
                FailedPenalty = FailedPenalty is not null ? FailedPenalty[1] : 0,
                OwnGoal = OwnGoal is not null ? OwnGoal[1] : 0,
                InjuryTime = InjuryTime is not null ? InjuryTime[1] : 0,
                OnGoal = OnGoal is not null ? OnGoal[1] : 0,
                MissGoal = MissGoal is not null ? MissGoal[1] : 0,
                Attck = Attck is not null ? Attck[1] : 0,
                DangerAttack = DangerAttack is not null ? DangerAttack[1] : 0,
                ControlRate = ControlRate is not null ? ControlRate[1] : 0,
                ExtraScore = ExtraScore is not null ? ExtraScore[1] : 0,
                PenaltyShootoutScore = PenaltyShootoutScore is not null ? PenaltyShootoutScore[1] : 0,
                Assists = Assists is not null ? Assists[1] : 0,
                First = First is not null ? First[1] : 0,
                Follow = Follow is not null ? Follow[1] : 0,
                Foul = Foul is not null ? Foul[1] : 0,
                Grade = Grade.Length > 0 ? Grade[1] : string.Empty,
                HalfConner = HalfConner is not null ? HalfConner[1] : 0,
                HalfPossession = HalfPossession.Length > 0 ? HalfPossession[1] : string.Empty,
                Head = Head is not null ? Head[1] : 0,
                HeadSuccess = HeadSuccess is not null ? HeadSuccess[1] : 0,
                Intercept = Intercept is not null ? Intercept[1] : 0,
                OffSide = OffSide is not null ? OffSide[1] : 0,
                OnDoor = OnDoor is not null ? OnDoor[1] : 0,
                Pass = Pass is not null ? Pass[1] : 0,
                PassSuccess = PassSuccess is not null ? PassSuccess[1] : 0,
                Past = Past is not null ? Past[1] : 0,
                Shoot = Shoot is not null ? Shoot[1] : 0,
                ShootOut = ShootOut is not null ? ShootOut[1] : 0,
                ShootStop = ShootStop is not null ? ShootStop[1] : 0,
                Slide = Slide is not null ? Slide[1] : 0,
                Steals = Steals is not null ? Steals[1] : 0
            };
    }
}