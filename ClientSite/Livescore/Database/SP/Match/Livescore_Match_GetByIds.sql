
DROP PROCEDURE IF EXISTS Livescore_Match_GetByIds;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetByIds`(
	IN matchIds JSON
    /*
		Creator: Hank@20220419
        Revisions: - 20220419@Hank: init
    */
)
BEGIN
	DROP TEMPORARY TABLE IF EXISTS tmp_ids;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_ids(MatchId BIGINT NOT NULL ,PRIMARY KEY (MatchId));
	
	INSERT INTO tmp_ids(MatchId)
    SELECT MatchId
    FROM 
		JSON_TABLE
        (
			matchIds,
            "$[*]"
            COLUMNS
            (
				MatchId BIGINT  PATH "$"
            )
        ) AS t;
        
        
	SELECT m.MatchId, m.StartTime, m.EndTime, m.Status
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
		,sh.ExtraScore AS HomeTeamExtraScore, sa.ExtraScore AS AwayTeamExtraScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
        ,m.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.CurrentSeasonId, l.Icon, le.IsMajor, le.Priority
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.Icon
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.Icon
    FROM livescore_match m
    INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
	INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
	INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
    LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
	LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
    LEFT JOIN livescore_league_extra le ON l.LeagueId = le.LeagueId
    WHERE  EXISTS (SELECT tmp.MatchId FROM tmp_ids tmp WHERE m.MatchId = tmp.MatchId)
     ORDER BY CASE WHEN le.LeagueId IS NOT NULL THEN le.Priority ELSE 9999999 END ASC
        ,CASE WHEN l.LeagueNameVN IS NOT NULL THEN l.LeagueNameVN
			WHEN l.LeagueNameEN IS NOT NULL THEN l.LeagueNameEN
            ELSE l.LeagueFullNameEN END ASC
		,m.StartTime ASC;
END
//
DELIMITER ;	
