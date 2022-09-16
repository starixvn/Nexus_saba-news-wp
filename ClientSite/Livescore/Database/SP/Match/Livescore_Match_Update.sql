/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_Match_Update;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_Update`(
	IN homeTeamId BIGINT,
	IN homeTeamNameVN VARCHAR(255),
    IN homeTeamIconVN VARCHAR(255),
    IN awayTeamId BIGINT,
    IN awayTeamNameVN VARCHAR(255),
    IN awayTeamIconVN VARCHAR(255),
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
	`TeamNameVN` = homeTeamNameVN,
    `IconVN` = homeTeamIconVN
	WHERE t.TeamId = homeTeamId;
    
    UPDATE `livescore_team` as t
	SET
	`TeamNameVN` = awayTeamNameVN,
    `IconVN` = awayTeamIconVN
	WHERE t.TeamId = awayTeamId;
END

//
DELIMITER ;	