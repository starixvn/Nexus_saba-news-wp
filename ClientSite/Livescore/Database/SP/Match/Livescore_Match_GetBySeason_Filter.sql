/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_Match_GetBySeason_Filter;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetBySeason_Filter`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    /*
		Creator: Hank@20220907
        Revisions:
			- 20220907@Hank: init
    */
)
BEGIN    
    
    
	DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT PRIMARY KEY);
    
    INSERT INTO tmp_MatchIds (MatchId)
    SELECT m.MatchId
    FROM livescore_match m
    WHERE m.LeagueId = leagueId
        AND IFNULL(m.SeasonId, 0) = seasonId
        AND m.CombinedId IS NULL
        AND m.Status IS NOT NULL
        AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
	
        
    SELECT m.MatchId, m.StartTime, m.EndTime, m.Status, m.Round, m.GroupNum, m.RelatedId
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
        ,sh.ExtraScore AS HomeTeamExtraScore, sa.ExtraScore AS AwayTeamExtraScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
        ,m.HomeTeamId, h.TeamNameEN , h.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.TeamNameVN, a.Icon, a.IconVN
        ,m.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.Mode AS StageMode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
    FROM livescore_match m
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
	WHERE EXISTS (SELECT tmp_m.MatchId FROM tmp_MatchIds tmp_m WHERE m.MatchId = tmp_m.MatchId)
    ORDER BY m.StartTime ASC
		,CASE WHEN h.TeamNameEN IS NOT NULL THEN h.TeamNameEN
			ELSE h.TeamFullNameEN END ASC;
            
	
END

//
DELIMITER ;	