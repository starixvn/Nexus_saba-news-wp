DROP PROCEDURE IF EXISTS Livescore_League_GetLeagueById;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetLeagueById`(
	In LeagueId LONG
	/*
		Creator: Brian@20220729
        Revisions:
			- 20220729@Brian: init
    */
)
BEGIN
	SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.CurrentSeasonId, l.Country as CountryEN, l.CountryVN
	FROM livescore_league l
	Where l.LeagueId = LeagueId;
END
//
DELIMITER ;