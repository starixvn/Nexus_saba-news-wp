DROP PROCEDURE IF EXISTS Livescore_Match_GetBySeason;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetBySeason`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    ,IN fromTime DATETIME
    ,IN toTime DATETIME
    /*
		Creator: Tim@20220412
        Revisions:
			- 20220412@Tim: init
			- 202200512@Tim: check 2nd leg
			- 202200517@Tim: get start time for live match
            - 202200610@Hank: check deleted match
    */
)
BEGIN    
    DECLARE _hasStandings BIT(1) DEFAULT 0;
    
	DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT PRIMARY KEY);
    
    SET _hasStandings = IF (EXISTS (SELECT 1 FROM livescore_season_standings ss WHERE ss.SeasonId = seasonId AND ss.StageId > 0), 1, 0);
    
    INSERT INTO tmp_MatchIds (MatchId)
    SELECT m.MatchId
    FROM livescore_match m
    WHERE m.LeagueId = leagueId
        AND IFNULL(m.SeasonId, 0) = seasonId
        AND m.CombinedId IS NULL
        AND m.Status IS NOT NULL
        AND (fromTime IS NULL OR m.StartTime BETWEEN fromTime AND toTime)
        AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
	
    SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, _hasStandings AS HasSeasonStandings, l.Icon
    FROM livescore_league l
	WHERE l.LeagueId = leagueId;
    
    SELECT m.MatchId, m.StartTime, m.EndTime, m.Status, m.Round, m.GroupNum
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
        ,sh.ExtraScore AS HomeTeamExtraScore, sa.ExtraScore AS AwayTeamExtraScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
        ,CASE WHEN m.Status = 10 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballStartTime END AS FootballStartTime
        ,CASE WHEN m.Status = 30 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballNextHalfStartTime END AS FootballNextHalfStartTime
        ,CASE WHEN m.Status = 40 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballAddStartTime END AS FootballAddStartTime
        ,m.HomeTeamId, h.TeamNameEN AS HomeTeamNameEN, h.TeamFullNameEN AS HomeTeamFullNameEN, h.Icon AS HomeTeamIcon
        ,m.AwayTeamId, a.TeamNameEN AS AwayTeamNameEN, a.TeamFullNameEN AS AwayTeamFullNameEN, a.Icon AS AwayTeamIcon
        ,m.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.Mode AS StageMode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
    FROM livescore_match m
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
        LEFT JOIN livescore_match_live live ON m.MatchId = live.MatchId
	WHERE EXISTS (SELECT tmp_m.MatchId FROM tmp_MatchIds tmp_m WHERE m.MatchId = tmp_m.MatchId)
    ORDER BY m.StartTime ASC
		,CASE WHEN h.TeamNameEN IS NOT NULL THEN h.TeamNameEN
			ELSE h.TeamFullNameEN END ASC;
END;

//
DELIMITER;
GRANT EXECUTE ON PROCEDURE Livescore_Match_GetBySeason TO 'sabasports'@'%';