
/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Options_UpdateKey;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Options_UpdateKey`(
	IN optionKey VARCHAR(100)
    ,IN optionValue TEXT
    ,IN actorId LONG
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
	 /*
		Creator: Hank@20230109
        Revisions: - 20230109@Hank: Init
    */
)
BEGIN
	DECLARE LogData LONGTEXT;
    
	IF (EXISTS (SELECT 1 FROM wp_options WHERE option_name = optionKey)) THEN
		UPDATE `wp_options` AS op
		SET op.option_value = optionValue
		WHERE op.option_name = optionKey 
        LIMIT 1;
	ELSE
		INSERT INTO `wp_options` (option_name, option_value)
        VALUES(optionKey, optionValue);
    END IF;
    
    SET LogData =  JSON_OBJECT('key', optionKey, 'value', optionValue);
    
    INSERT INTO `wp_Log`
	(`ActorId`,
	`ActionId`,
	`Data`,
    `LogDate`
    )
	VALUES
	(actorId,
     4,
     LogData,
     LOCALTIME()
     );
END

//
DELIMITER ;	


DROP PROCEDURE IF EXISTS Options_GetKey;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Options_GetKey`(
	optionKey VARCHAR(100)
    /*
		Creator: Hank@20230109
        Revisions: - 20230109@Hank: Init
    */
)
BEGIN
	SELECT op.option_name as OptionName, op.option_value as OptionValue FROM wp_options op WHERE op.option_name = optionKey LiMIT 1;
END

//
DELIMITER ;	



DROP PROCEDURE IF EXISTS Livescore_League_GetLeagueAndSeasonStandings;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetLeagueAndSeasonStandings`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    /*
		Creator: Hank@20230111
        Revisions:
			- 20230111@Hank: init
    */
)
BEGIN
	SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.IconVN, l.CurrentSeasonId, l.Country as CountryEN, l.CountryVN, l.CurrentStageId, c.CountryLogo
	FROM livescore_league l LEFT JOIN livescore_country c ON c.CountryId = l.CountryId
	Where l.LeagueId = LeagueId;
    
    SELECT t.TeamId, t.TeamNameVN, t.TeamNameEN, t.TeamFullNameEN, t.Icon, t.IconVN
		,s.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.OrderIndex
		,COALESCE(se.GroupName, s.GroupName) as GroupName, COALESCE(se.Total, s.Total) as Total, COALESCE(se.Won, s.Won) as Won
        ,COALESCE(se.Loss, s.Loss) as Loss, COALESCE(se.Draw, s.Draw) as Draw, COALESCE(se.Points, s.Points) as Points
        ,COALESCE(se.Goals, s.Goals) as Goals, COALESCE(se.GoalsAgainst, s.GoalsAgainst) as GoalsAgainst, COALESCE(se.GoalDiff, s.GoalDiff) as GoalDiff
        ,(IFNULL(se.Position, s.Position)) as Position,  s.Promotion
    FROM livescore_season_standings s
		INNER JOIN livescore_team t ON s.TeamId = t.TeamId
        INNER JOIN livescore_season_stage ss ON s.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_season_standings_extra se ON se.SeasonId = s.SeasonId AND se.StageId = s.StageId AND se.TeamId = s.TeamId
    WHERE s.SeasonId = seasonId AND s.StageId > 0
    ORDER BY ss.OrderIndex ASC, GroupName ASC, Position ASC;
END

//
DELIMITER ;	