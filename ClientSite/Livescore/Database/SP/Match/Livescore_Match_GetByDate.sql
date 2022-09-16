/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_Match_GetByDate;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetByDate`(
	IN fromTime DATETIME
    ,IN toTime DATETIME
    ,IN sportId SMALLINT UNSIGNED
    /*
		Creator: Tim@20220322
        Revisions:
			- 20220322@Tim: init
			- 202200512@Tim: check 2nd leg
			- 202200517@Tim: get start time for live match
            - 20220711@Hank: get country data
            - 20220712@Hank: get only major league
            - 20220915@Hank: get Team Name & Icon VN
    */
)
BEGIN
	DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT PRIMARY KEY);
    
    INSERT INTO tmp_MatchIds (MatchId)
    SELECT m.MatchId
    FROM livescore_match m
        INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
    WHERE m.SportId = sportId
		AND m.Status IS NOT NULL
		AND m.StartTime BETWEEN fromTime AND toTime
        AND m.CombinedId IS NULL
        AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
    
    SELECT m.MatchId, m.StartTime, m.EndTime, m.Status, m.Round, m.GroupNum
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
        ,sh.ExtraScore AS HomeTeamExtraScore, sa.ExtraScore AS AwayTeamExtraScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
        ,CASE WHEN m.Status = 10 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballStartTime END AS FootballStartTime
        ,CASE WHEN m.Status = 30 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballNextHalfStartTime END AS FootballNextHalfStartTime
        ,CASE WHEN m.Status = 40 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballAddStartTime END AS FootballAddStartTime
		,m.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, le.IsMajor, le.Priority, l.CountryVN
        ,c.CountryId, c.CountryLogo, c.CountryLogoCustom
        ,s.SeasonId, s.Year
        ,ss.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.Mode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.TeamNameVN, a.Icon, a.IconVN
    FROM livescore_match m
        INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        INNER JOIN livescore_league_extra le ON l.LeagueId = le.LeagueId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_season s ON m.SeasonId = s.SeasonId
        LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
        LEFT JOIN livescore_match_live live ON m.MatchId = live.MatchId
        LEFT JOIN livescore_country c ON c.CountryId = l.CountryId
	WHERE EXISTS (SELECT tmp_m.MatchId FROM tmp_MatchIds tmp_m WHERE m.MatchId = tmp_m.MatchId)
    ORDER BY CASE WHEN le.LeagueId IS NOT NULL THEN le.Priority ELSE 999999 END ASC
        ,CASE WHEN l.LeagueNameVN IS NOT NULL THEN l.LeagueNameVN
			WHEN l.LeagueNameEN IS NOT NULL THEN l.LeagueNameEN
            ELSE l.LeagueFullNameEN END ASC
		,m.StartTime ASC
        ,CASE WHEN h.TeamNameEN IS NOT NULL THEN h.TeamNameEN
			ELSE h.TeamFullNameEN END ASC;
END

//
DELIMITER ;	
