DROP PROCEDURE IF EXISTS Add_column_CreatedDate_LeagueExtra;
DELIMITER //
CREATE DEFINER=`root`@`%` PROCEDURE `Add_column_CreatedDate_LeagueExtra`(
	/*
		Creator: Brian@20220808
        Revisions:
			- 20220808@Brian: init
    */   
)
BEGIN
	ALTER TABLE `sabasports`.`livescore_league_extra`
	ADD COLUMN CreatedDate DATETIME;
END
//
DELIMITER ;