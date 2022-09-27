/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_Standing_Update;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Standing_Update`(
	IN seasonId BIGINT,
    IN stageId BIGINT,
    IN teamId BIGINT,
	IN teamNameVN VARCHAR(255),
    IN iconVN VARCHAR(255),
    IN groupName VARCHAR(255),
    IN total SMALLINT,
    IN won SMALLINT,
    IN draw SMALLINT,
    IN loss SMALLINT,
    IN goals SMALLINT,
    IN goalsAgainst SMALLINT,
    IN goalDiff SMALLINT,
    IN points SMALLINT,
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)   
	/*
		Creator: Hank@20220907
        Revisions:
			- 20220907@Hank: init
    */
)
BEGIN
	UPDATE `livescore_team` as t
	SET
	`TeamNameVN` = teamNameVN,
    `IconVN` = iconVN
	WHERE t.TeamId = teamId;
    
    INSERT INTO `sabasports`.`livescore_season_standings_extra`
	(`SeasonId`,
	`StageId`,
	`TeamId`,
    `GroupName`,
	`Total`,
	`Won`,
	`Loss`,
	`Draw`,
	`Goals`,
	`GoalsAgainst`,
	`GoalDiff`,
	`Points`,
	`UpdatedDate`)
	VALUES
	(seasonId,
	stageId,
	teamId,
    groupName,
	total,
	won,
	loss,
	draw,
	goals,
	goalsAgainst,
	goalDiff,
	points,
	NOW())
    ON DUPLICATE KEY UPDATE 
    GroupName = groupName,
    Total = total,
    Won = won,
    Loss = loss,
    Draw = draw,
    Goals = goals,
    GoalsAgainst = goalsAgainst,
    GoalDiff = goalDiff,
    Points = points,
    UpdatedDate = NOW();

    
    
END

//
DELIMITER ;	