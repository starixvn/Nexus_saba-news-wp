DROP PROCEDURE IF EXISTS Livescore_League_Update;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_Update`(
	/*
		Creator: Brian@20220808
        Revisions:
			- 20220808@Brian: init
    */
    IN CountryVN VARCHAR(255),
    IN LeagueNameVN VARCHAR(255), 
    IN LeagueId LONG,
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)    
)
BEGIN
	UPDATE `livescore_league` as le
	SET
	`CountryVN` = CountryVN,
    `LeagueNameVN` = LeagueNameVN
	WHERE le.LeagueId = LeagueId;
END

//
DELIMITER ;