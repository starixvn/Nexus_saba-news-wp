/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_League_GetSeasonStandings;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetSeasonStandings`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    /*
		Creator: Hank@20220811
        Revisions:
			- 20220811@Hank: init
    */
)
BEGIN
	DECLARE _teamId BIGINT DEFAULT(0);
	DECLARE _stageId BIGINT DEFAULT(0);
    
    DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT, HomeTeamId BIGINT, AwayTeamId BIGINT);
    
	DROP TEMPORARY TABLE IF EXISTS tmp_Standings;
    CREATE TEMPORARY TABLE tmp_Standings (TeamId BIGINT, StageId BIGINT);
    
    INSERT INTO tmp_Standings (TeamId, StageId)
    SELECT DISTINCT s.TeamId, s.StageId
    FROM livescore_season_standings s
	WHERE s.SeasonId = seasonId AND s.StageId > 0;
    
    SELECT t.TeamId, t.TeamNameVN, t.TeamNameEN, t.TeamFullNameEN, t.Icon, t.IconVN, s.GroupName
		,s.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.OrderIndex
        ,(IFNULL(se.Position, s.Position)) as Position, s.Total, s.Won, s.Loss, s.Draw, s.Points, s.Goals, s.GoalsAgainst, s.GoalDiff, s.Promotion
    FROM livescore_season_standings s
		INNER JOIN livescore_team t ON s.TeamId = t.TeamId
        INNER JOIN livescore_season_stage ss ON s.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_season_standings_extra se ON se.SeasonId = s.SeasonId AND se.StageId = s.StageId AND se.TeamId = s.TeamId
    WHERE s.SeasonId = seasonId AND s.StageId > 0
    ORDER BY ss.OrderIndex ASC, s.GroupName ASC, Position ASC;
END

//
DELIMITER ;	