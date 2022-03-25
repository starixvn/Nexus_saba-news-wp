namespace SabaNews.Livescore.SyncData.Helpers
{
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.SyncData.Models.Matches;

    internal static class DataHelpers
    {
        public static MatchTeamStatistic ConvertToTeamStatistic(MatchExtraData extraData, long matchId, bool isHome)
        {
            int index = isHome ? 0 : 1;
            return new MatchTeamStatistic()
            {
                MatchId = matchId,
                IsHomeTeam = isHome,
                Goal = extraData.Goal is not null ? extraData.Goal[index] : 0,
                CornerKick = extraData.ConnerKick is not null ? extraData.ConnerKick[index] : 0,
                YellowCard = extraData.YellowCard is not null ? extraData.YellowCard[index] : 0,
                RedCard = extraData.RedCard is not null ? extraData.RedCard[index] : 0,
                OutBall = extraData.OutBall is not null ? extraData.OutBall[index] : 0,
                FreeKick = extraData.FreeKick is not null ? extraData.FreeKick[index] : 0,
                GoalKick = extraData.GoalKick is not null ? extraData.GoalKick[index] : 0,
                PenaltyKick = extraData.PenaltyKick is not null ? extraData.PenaltyKick[index] : 0,
                YellowToRed = extraData.YellowToRed is not null ? extraData.YellowToRed[index] : 0,
                FailPenaltyKick = extraData.FailedPenalty is not null ? extraData.FailedPenalty[index] : 0,
                OwnGoal = extraData.OwnGoal is not null ? extraData.OwnGoal[index] : 0,
                InjuryTime = extraData.InjuryTime is not null ? extraData.InjuryTime[index] : 0,
                OnGoal = extraData.OnGoal is not null ? extraData.OnGoal[index] : 0,
                MissGoal = extraData.MissGoal is not null ? extraData.MissGoal[index] : 0,
                Attack = extraData.Attck is not null ? extraData.Attck[index] : 0,
                DangerAttack = extraData.DangerAttack is not null ? extraData.DangerAttack[index] : 0,
                ControlRate = extraData.ControlRate is not null ? extraData.ControlRate[index] : 0,
                ExtraScore = extraData.ExtraScore is not null ? extraData.ExtraScore[index] : 0,
                PenaltyShootoutScore = extraData.PenaltyShootoutScore is not null ? extraData.PenaltyShootoutScore[index] : 0,
                Assists = extraData.Assists is not null ? extraData.Assists[index] : 0,
                First = extraData.First is not null ? extraData.First[index] : 0,
                Follow = extraData.Follow is not null ? extraData.Follow[index] : 0,
                Foul = extraData.Foul is not null ? extraData.Foul[index] : 0,
                Grade = extraData.Grade is not null && extraData.Grade.Length > 0 ? extraData.Grade[index] : string.Empty,
                HalfCorner = extraData.HalfConner is not null ? extraData.HalfConner[index] : 0,
                HalfPossession = extraData.HalfPossession is not null && extraData.HalfPossession.Length > 0 ? extraData.HalfPossession[index] : string.Empty,
                Head = extraData.Head is not null ? extraData.Head[index] : 0,
                HeadSuccess = extraData.HeadSuccess is not null ? extraData.HeadSuccess[index] : 0,
                Intercept = extraData.Intercept is not null ? extraData.Intercept[index] : 0,
                OffSide = extraData.OffSide is not null ? extraData.OffSide[index] : 0,
                OnDoor = extraData.OnDoor is not null ? extraData.OnDoor[index] : 0,
                Pass = extraData.Pass is not null ? extraData.Pass[index] : 0,
                PassSuccess = extraData.PassSuccess is not null && extraData.PassSuccess.Length > 0 ? extraData.PassSuccess[index] : string.Empty,
                Past = extraData.Past is not null ? extraData.Past[index] : 0,
                Shoot = extraData.Shoot is not null ? extraData.Shoot[index] : 0,
                ShootOut = extraData.ShootOut is not null ? extraData.ShootOut[index] : 0,
                ShootStop = extraData.ShootStop is not null ? extraData.ShootStop[index] : 0,
                Slide = extraData.Slide is not null ? extraData.Slide[index] : 0,
                Steals = extraData.Steals is not null ? extraData.Steals[index] : 0
            };
        }
    }
}