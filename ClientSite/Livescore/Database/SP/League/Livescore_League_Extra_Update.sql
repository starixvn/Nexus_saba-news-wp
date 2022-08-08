DROP PROCEDURE IF EXISTS Livescore_League_Extra_Update;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_Extra_Update`(
	/*
		Creator: Brian@20220808
        Revisions:
			- 20220808@Brian: init
    */
	IN IsMajor bool,
    IN Priority INT, 
    IN LeagueId INT,
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)    
)
BEGIN
	UPDATE `livescore_league_extra` as le
	SET
	`IsMajor` = IsMajor,
	`Priority` = Priority
	WHERE le.LeagueId = LeagueId;
END
//
DELIMITER ;