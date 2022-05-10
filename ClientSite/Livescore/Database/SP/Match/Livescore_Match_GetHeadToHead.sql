DROP PROCEDURE IF EXISTS Livescore_Match_GetHeadToHead;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetHeadToHead`(
	matchId BIGINT
    /*
		Creator: Hank@20220419
        Revisions: - 20220419@Hank: init
    */
)
BEGIN
    DECLARE homeTeamId BIGINT;
    DECLARE awayTeamId BIGINT;
    
    SELECT m.HomeTeamId, m.AwayTeamId INTO homeTeamId, awayTeamId
    FROM livescore_match m 
    WHERE m.MatchId = matchId;
    
    SELECT m.MatchId, m.StartTime, m.EndTime, m.Status
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
		,sh.ExtraScore AS HomeTeamExtraScore, sa.ExtraScore AS AwayTeamExtraScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
        ,m.LeagueId, l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.CurrentSeasonId, l.Icon
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.Icon
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.Icon
    FROM livescore_match m
    INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
    INNER JOIN livescore_team h ON h.TeamId = m.HomeTeamId
    INNER JOIN livescore_team a ON a.TeamId = m.AwayTeamId
    LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
	LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
    WHERE m.MatchId IN ( SELECT h2h.ReferenceMatchId FROM livescore_head_to_head h2h WHERE h2h.MatchId = matchId)
    ORDER BY m.StartTime DESC;
    
    select homeTeamId AS homeId, awayTeamId AS awayId;
    
    
    
   
END
//
DELIMITER ;	