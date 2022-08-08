DROP PROCEDURE IF EXISTS Update_LeagueExtra_CreatedDate;
DELIMITER //
CREATE DEFINER=`root`@`%` PROCEDURE `Update_LeagueExtra_CreatedDate`(
	/*
		Creator: Brian@20220808
        Revisions:
			- 20220808@Brian: init
    */  
)
BEGIN
	SET SQL_SAFE_UPDATES = 0;
	UPDATE `sabasports`.`livescore_league_extra`
	SET
	`CreatedDate` = now();
END

//
DELIMITER ;