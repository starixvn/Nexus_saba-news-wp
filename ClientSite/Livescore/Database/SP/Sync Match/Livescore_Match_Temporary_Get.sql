DROP PROCEDURE IF EXISTS Livescore_Match_Temporary_Get;
DELIMITER //

CREATE DEFINER = 'sabasports'@'%' PROCEDURE Livescore_Match_Temporary_Get(
    IN rowLimit INT
    /*
		Creator: Oliver@20220324
        Revisions: - 20220324@Oliver: Get tempory match IDs to get details
    */
)
SQL SECURITY DEFINER
BEGIN
    SELECT tm.MatchId
          ,m.IsLineUp
          ,(CASE
                WHEN m.StartTime <= UTC_TIMESTAMP() THEN 1
                ELSE 0
             END
          ) As IsStarted
    FROM livescore_match_temporary tm
    INNER JOIN livescore_match m ON tm.MatchId = m.MatchId
    ORDER BY tm.MatchId
    LIMIT rowLimit;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Match_Temporary_Get TO 'sabasports'@'%';