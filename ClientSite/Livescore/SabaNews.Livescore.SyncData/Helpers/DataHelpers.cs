namespace SabaNews.Livescore.SyncData.Helpers
{
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.SyncData.Models.Matches;
    using SabaNews.Livescore.SyncData.Models.Team;

    internal static class DataHelpers
    {
        internal static (List<MatchScoreInput>, List<MatchTeamStatistic>) GenerateMatchDetailInput(List<MatchDetailResponse> matches)
        {
            var matchScoreList = new List<MatchScoreInput>();
            var statisticList = new List<MatchTeamStatistic>();
            foreach (var match in matches)
            {
                var matchScore = new MatchScoreInput
                {
                    MatchId = match.MatchId,
                    HomeHalfScore = match.HHalfScore ?? 0,
                    AwayHalfScore = match.AHalfScore ?? 0,
                    HomeTotalScore = match.HTotalScore ?? 0,
                    AwayTotalScore = match.ATotalScore ?? 0,
                    HomeAggregateScore = match.HomeAggScore,
                    AwayAggregateScore = match.AwayAggScore,
                    Status = match.GameType,
                    GameStartTime = match.GameStartTime,
                    GameEndTime = match.GameEndTime,
                };
                matchScoreList.Add(matchScore);
                var homeStatistic = ConvertToTeamStatistic(match.ExtraData, match.MatchId, true);
                var awayStatistic = ConvertToTeamStatistic(match.ExtraData, match.MatchId, false);
                statisticList.Add(homeStatistic);
                statisticList.Add(awayStatistic);
            }
            return (matchScoreList, statisticList);
        }

        internal static (List<MatchLineup>, List<PlayerLineup>) GenerateMatchLineupInput(List<MatchLineupResponse> lineups)
        {
            var matchLineups = new List<MatchLineup>();
            var players = new List<PlayerLineup>();
            foreach (var lineup in lineups)
            {
                var matchLineup = new MatchLineup
                {
                    MatchId = lineup.MatchId,
                    HomeFormation = lineup.HFormation,
                    AwayFormation = lineup.AFormation
                };
                matchLineups.Add(matchLineup);

                players = players.Concat(ToPlayerList(lineup.MatchId, lineup.HLineup, true)).ToList();
                players = players.Concat(ToPlayerList(lineup.MatchId, lineup.HBench, true, true)).ToList();
                players = players.Concat(ToPlayerList(lineup.MatchId, lineup.ALineup)).ToList();
                players = players.Concat(ToPlayerList(lineup.MatchId, lineup.ABench, isSubstitute: true)).ToList();
            }
            return (matchLineups, players);
        }

        private static List<PlayerLineup> ToPlayerList(long matchId, List<TeamLineupResponse> lineups, bool isHome = false, bool isSubstitute = false)
            => lineups.Select(p => new PlayerLineup
            {
                MatchId = matchId,
                PlayerId = p.PlayerId,
                IsHomeTeam = isHome,
                IsSubstitute = isSubstitute,
                CoordinateX = p.Coordinate is not null ? p.Coordinate.X : 0,
                CoordinateY = p.Coordinate is not null ? p.Coordinate.Y : 0,
                PlayerName = string.Empty,
                PlayerNumber = p.PlayerNumber,
                Position = p.Position,
                Role = p.Role
            }).ToList();

        private static MatchTeamStatistic ConvertToTeamStatistic(MatchExtraData extraData, long matchId, bool isHome)
        {
            byte index = (byte)(isHome ? 0 : 1);
            var result = new MatchTeamStatistic
            {
                MatchId = matchId,
                IsHomeTeam = isHome,
                Goal = GetGoal(extraData, index),
                CornerKick = GetCornerKick(extraData, index),
                YellowCard = GetYellowCard(extraData, index),
                RedCard = GetRedCard(extraData, index),
                OutBall = GetOutBall(extraData, index),
                FreeKick = GetFreeKick(extraData, index),
                GoalKick = GetGoalKick(extraData, index),
                PenaltyKick = GetPenaltyKick(extraData, index),
                YellowToRed = GetYellowToRed(extraData, index),
                FailPenaltyKick = GetFailPenaltyKick(extraData, index),
                OwnGoal = GetOwnGoal(extraData, index),
                InjuryTime = GetInjuryTime(extraData, index),
                OnGoal = GetOnGoal(extraData, index),
                MissGoal = GetMissGoal(extraData, index),
                Attack = GetAttack(extraData, index),
                DangerAttack = GetDangerAttack(extraData, index),
                ControlRate = GetControlRate(extraData, index),
                ExtraScore = GetExtraScore(extraData, index),
                PenaltyScore = GetPenaltyScore(extraData, index),
                Assists = GetAssists(extraData, index),
                First = GetFirst(extraData, index),
                Follow = GetFollow(extraData, index),
                Foul = GetFoul(extraData, index),
                Grade = GetGrade(extraData, index),
                HalfCorner = GetHalfCorner(extraData, index),
                HalfPossession = GetHalfPossession(extraData, index),
                Head = GetHead(extraData, index),
                HeadSuccess = GetHeadSuccess(extraData, index),
                Intercept = GetIntercept(extraData, index),
                OffSide = GetOffSide(extraData, index),
                OnDoor = GetOnDoor(extraData, index),
                Pass = GetPass(extraData, index),
                PassSuccess = GetPassSuccess(extraData, index),
                Past = GetPast(extraData, index),
                Shoot = GetShoot(extraData, index),
                ShootOut = GetShootOut(extraData, index),
                ShootStop = GetShootStop(extraData, index),
                Slide = GetSlide(extraData, index),
                Steals = GetSteals(extraData, index),
            };

            return result;
        }

        internal static List<TeamHistory> GenerateTeamHistoryToImport(HeadToHeadResponse h2h)
        {
            var results = new List<TeamHistory>();

            foreach (var hr in h2h.HomeTeamRecord)
            {
                results.Add(GenerateTeamHistory(h2h.SourceMatchId, h2h.HomeTeamId, hr));
            }

            foreach (var ar in h2h.AwayTeamRecord)
            {
                results.Add(GenerateTeamHistory(h2h.SourceMatchId, h2h.AwayTeamId, ar));
            }

            foreach (var ag in h2h.AgainstRecord)
            {
                results.Add(new TeamHistory
                {
                    MatchId = h2h.SourceMatchId,
                    ReferenceMatchId = ag.MatchId,
                    HomeTeamId = ag.HomeIdVsAwayId[0],
                    AwayTeamId = ag.HomeIdVsAwayId[1],
                    HomeHalfScore = ag.HalfScores[0],
                    AwayHalfScore = ag.HalfScores[1],
                    HomeTotalScore = ag.TotalScores[0],
                    AwayTotalScore = ag.TotalScores[1]
                });
            }

            return results;
        }

        private static TeamHistory GenerateTeamHistory(long sourceMatchId, long teamId, TeamHistoryRecord r)
            => new()
            {
                MatchId = sourceMatchId,
                ReferenceMatchId = r.MatchId,
                HomeTeamId = r.IsHost ? teamId : r.EnemyId,
                AwayTeamId = r.IsHost ? r.EnemyId : teamId,
                HomeTotalScore = r.IsHost ? r.TotalScores[0] : r.TotalScores[1],
                AwayTotalScore = r.IsHost ? r.TotalScores[1] : r.TotalScores[0],
                HomeHalfScore = r.IsHost ? r.HalfScores[0] : r.HalfScores[1],
                AwayHalfScore = r.IsHost ? r.HalfScores[1] : r.HalfScores[0]
            };

        private static int? GetGoal(MatchExtraData data, byte index) => data.Goal is not null ? data.Goal[index] : null;

        private static int? GetCornerKick(MatchExtraData data, byte index) => data.CornerKick is not null ? data.CornerKick[index] : null;

        private static int? GetYellowCard(MatchExtraData data, byte index) => data.YellowCard is not null ? data.YellowCard[index] : null;

        private static int? GetRedCard(MatchExtraData data, byte index) => data.RedCard is not null ? data.RedCard[index] : null;

        private static int? GetOutBall(MatchExtraData data, byte index) => data.OutBall is not null ? data.OutBall[index] : null;

        private static int? GetFreeKick(MatchExtraData data, byte index) => data.FreeKick is not null ? data.FreeKick[index] : null;

        private static int? GetGoalKick(MatchExtraData data, byte index) => data.GoalKick is not null ? data.GoalKick[index] : null;

        private static int? GetPenaltyKick(MatchExtraData data, byte index) => data.PenaltyKick is not null ? data.PenaltyKick[index] : null;

        private static int? GetYellowToRed(MatchExtraData data, byte index) => data.YellowToRed is not null ? data.YellowToRed[index] : null;

        private static int? GetFailPenaltyKick(MatchExtraData data, byte index) => data.FailedPenalty is not null ? data.FailedPenalty[index] : null;

        private static int? GetOwnGoal(MatchExtraData data, byte index) => data.OwnGoal is not null ? data.OwnGoal[index] : null;

        private static int? GetInjuryTime(MatchExtraData data, byte index) => data.InjuryTime is not null ? data.InjuryTime[index] : null;

        private static int? GetOnGoal(MatchExtraData data, byte index) => data.OnGoal is not null ? data.OnGoal[index] : null;

        private static int? GetMissGoal(MatchExtraData data, byte index) => data.MissGoal is not null ? data.MissGoal[index] : null;

        private static int? GetAttack(MatchExtraData data, byte index) => data.Attack is not null ? data.Attack[index] : null;

        private static int? GetDangerAttack(MatchExtraData data, byte index) => data.DangerAttack is not null ? data.DangerAttack[index] : null;

        private static int? GetControlRate(MatchExtraData data, byte index)
        {
            if (data.ControlRate is null || string.IsNullOrWhiteSpace(data.ControlRate[index]))
            {
                return null;
            }

            string controlRate = data.ControlRate[index].Replace("%", string.Empty);
            return int.Parse(controlRate, NumberStyles.Integer);
        }

        private static int? GetExtraScore(MatchExtraData data, byte index) => data.ExtraScore is not null ? data.ExtraScore[index] : null;

        private static int? GetPenaltyScore(MatchExtraData data, byte index) => data.PenaltyScore is not null ? data.PenaltyScore[index] : null;

        private static int? GetAssists(MatchExtraData data, byte index) => data.Assists is not null ? data.Assists[index] : null;

        private static int? GetFirst(MatchExtraData data, byte index) => data.First is not null ? data.First[index] : null;

        private static int? GetFollow(MatchExtraData data, byte index) => data.Follow is not null ? data.Follow[index] : null;

        private static int? GetFoul(MatchExtraData data, byte index) => data.Foul is not null ? data.Foul[index] : null;

        private static string GetGrade(MatchExtraData data, byte index)
            => data.Grade is not null && data.Grade.Length > 0 ? data.Grade[index] : string.Empty;

        private static int? GetHalfCorner(MatchExtraData data, byte index) => data.HalfCorner is not null ? data.HalfCorner[index] : null;

        private static string GetHalfPossession(MatchExtraData data, byte index)
            => data.HalfPossession is not null && data.HalfPossession.Length > 0 ? data.HalfPossession[index] : string.Empty;

        private static int? GetHead(MatchExtraData data, byte index) => data.Head is not null ? data.Head[index] : null;

        private static int? GetHeadSuccess(MatchExtraData data, byte index) => data.HeadSuccess is not null ? data.HeadSuccess[index] : null;

        private static int? GetIntercept(MatchExtraData data, byte index) => data.Intercept is not null ? data.Intercept[index] : null;

        private static int? GetOffSide(MatchExtraData data, byte index) => data.OffSide is not null ? data.OffSide[index] : null;

        private static int? GetOnDoor(MatchExtraData data, byte index) => data.OnDoor is not null ? data.OnDoor[index] : null;

        private static int? GetPass(MatchExtraData data, byte index) => data.Pass is not null ? data.Pass[index] : null;

        private static string GetPassSuccess(MatchExtraData data, byte index)
            => data.PassSuccess is not null && data.PassSuccess.Length > 0 ? data.PassSuccess[index] : string.Empty;

        private static int? GetPast(MatchExtraData data, byte index) => data.Past is not null ? data.Past[index] : null;

        private static int? GetShoot(MatchExtraData data, byte index) => data.Shoot is not null ? data.Shoot[index] : null;

        private static int? GetShootOut(MatchExtraData data, byte index) => data.ShootOut is not null ? data.ShootOut[index] : null;

        private static int? GetShootStop(MatchExtraData data, byte index) => data.ShootStop is not null ? data.ShootStop[index] : null;

        private static int? GetSlide(MatchExtraData data, byte index) => data.Slide is not null ? data.Slide[index] : null;

        private static int? GetSteals(MatchExtraData data, byte index) => data.Steals is not null ? data.Steals[index] : null;
    }
}