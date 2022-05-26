DROP PROCEDURE IF EXISTS Livescore_League_Get;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_Get`(
	/*
		Creator: Hank@20220524
        Revisions:
			- 20220524@Hank: init
    */
)
BEGIN
	SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.CurrentSeasonId, e.IsMajor, e.Priority
	FROM livescore_league l
	LEFT JOIN livescore_league_extra e ON l.LeagueId = e.LeagueId
	ORDER BY e.Priority is null, e.Priority;
END

//
DELIMITER ;	