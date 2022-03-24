DROP PROCEDURE IF EXISTS Livescore_Match_GetTemporaryIds;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_Match_GetTemporaryIds(
    /*
		Creator: Oliver@20220324
        Revisions: - 20220324@Oliver: Get tempory match IDs to get details
    */
)
SQL SECURITY DEFINER
BEGIN
    SELECT tm.MatchId
    FROM livescore_temporary_match tm
    LIMIT 1000;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Match_GetTemporaryIds TO 'sabasports'@'%';