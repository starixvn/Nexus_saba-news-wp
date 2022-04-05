DROP PROCEDURE IF EXISTS Livescore_LiveMatch_Get;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_LiveMatch_Get(
    /*
		Creator: Oliver@20220405
        Revisions: - 20220405@Oliver: Get current Live match
        Params:
    */
)
SQL SECURITY DEFINER
BEGIN
    SELECT m.MatchId, m.StartTime, m.EndTime, m.Status
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
		,m.LeagueId, l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.Icon, l.IsMajor
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.Icon
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.Icon
        ,sh.Goal AS HomeGoal, sh.CornerKick, sh.YellowCard, sh.RedCard, sh.YellowToRed, sh.OutBall, sh.FreeKick, sh.GoalKick, sh.PenaltyKick
            , sh.FailPenaltyKick, sh.OwnGoal, sh.InjuryTime, sh.OnGoal, sh.MissGoal, sh.Attack, sh.DangerAttack, sh.ControlRate
            , sh.ExtraScore, sh.PenaltyScore, sh.Assists, sh.First, sh.Follow, sh.Grade, sh.Foul, sh.HalfCorner, sh.HalfPossession, sh.Head
            , sh.HeadSuccess, sh.Intercept, sh.OffSide, sh.OnDoor, sh.Pass, sh.PassSuccess, sh.Past, sh.Shoot, sh.ShootOut, sh.ShootStop
            , sh.Slide, sh.Steals
        ,sa.Goal AS AwayGoal, sa.CornerKick, sa.YellowCard, sa.RedCard, sa.YellowToRed, sa.OutBall, sa.FreeKick, sa.GoalKick, sa.PenaltyKick
            , sa.FailPenaltyKick, sa.OwnGoal, sa.InjuryTime, sa.OnGoal, sa.MissGoal, sa.Attack, sa.DangerAttack, sa.ControlRate
            , sa.ExtraScore, sa.PenaltyScore, sa.Assists, sa.First, sa.Follow, sa.Grade, sa.Foul, sa.HalfCorner, sa.HalfPossession, sa.Head
            , sa.HeadSuccess, sa.Intercept, sa.OffSide, sa.OnDoor, sa.Pass, sa.PassSuccess, sa.Past, sa.Shoot, sa.ShootOut, sa.ShootStop
            , sa.Slide, sa.Steals
    FROM livescore_match_live lm
        INNER JOIN livescore_match m ON m.MatchId = lm.MatchId
        INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
    ORDER BY l.IsMajor, m.StartTime;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_LiveMatch_Get TO 'sabasports'@'%';