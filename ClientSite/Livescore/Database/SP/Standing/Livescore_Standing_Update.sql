/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_Standing_Update;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Standing_Update`(
	IN seasonId BIGINT,
    IN stageId BIGINT,
    IN teamId BIGINT,
	IN teamNameVN VARCHAR(255),
    IN iconVN VARCHAR(255),
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
    
    
END

//
DELIMITER ;	