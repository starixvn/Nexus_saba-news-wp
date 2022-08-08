/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_League_GetAll;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetAll`(
	/*
		Creator: Brian@20220719
        Revisions:
			- 20220719@Brian: init
    */
)
BEGIN
	SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.CurrentSeasonId, l.CurrentSeasonId, l.Country as CountryEN, l.CountryVN
	FROM livescore_league l
	WHERE Not EXISTS
	(SELECT e.LeagueId FROM livescore_league_extra e where l.LeagueId = e.LeagueId);
END
//
DELIMITER ;