/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS Livescore_League_Get;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_Get`(
	/*
		Creator: Hank@20220524
        Revisions:
			- 20220524@Hank: init
            - 20220712@Hank: get only major league
            - 20220808@Brian: get a few columns
    */
)
BEGIN
	SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.CurrentSeasonId, l.Country as CountryEN, l.CountryVN, e.IsMajor, e.Priority, e.CreatedDate
	FROM livescore_league l
		INNER JOIN livescore_league_extra e ON l.LeagueId = e.LeagueId
	ORDER BY e.Priority is null, e.Priority;
END


//
DELIMITER ;	