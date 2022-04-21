DROP PROCEDURE IF EXISTS Livescore_Match_Temporary_Insert;
DELIMITER //

CREATE DEFINER = 'sabasports'@'%' PROCEDURE Livescore_Match_Temporary_Insert(
    IN matchIds TEXT
    ,IN isSyncFinishedMatches BIT
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220324
        Revisions: - 20220324@Oliver: Insert temporary Match Ids & IsLineUp
        Params: - matchIds: "65723,23232,242123"
    */
)
SQL SECURITY DEFINER
BEGIN
    SET errCode=0, errMsg='';
    
    DROP TEMPORARY TABLE IF EXISTS tmp_Match;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_Match(MatchId bigint PRIMARY KEY NOT NULL);

    IF IFNULL(matchIds,'') <> ''THEN
		SET @sqlCommand = REPLACE(matchIds, ',', '),(');
		SET @sqlCommand = CONCAT('(', @sqlCommand, ')');
		SET @sqlCommand = CONCAT('INSERT INTO tmp_Match VALUES', @sqlCommand); 
                                                            
		PREPARE stmt FROM  @sqlCommand;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
	END IF;

    IF (isSyncFinishedMatches = 0) THEN
        INSERT INTO livescore_match_temporary(MatchId)
        SELECT DISTINCT tmp.MatchId
        FROM tmp_Match tmp
        LEFT JOIN livescore_match_temporary mt ON tmp.MatchId = mt.MatchId
        LEFT JOIN livescore_match m ON tmp.MatchId = m.MatchId
        LEFT JOIN livescore_match_statistic ms ON tmp.MatchId = ms.MatchId
        LEFT JOIN livescore_match_incident mi ON tmp.MatchId = mi.MatchId
        LEFT JOIN livescore_lineup ml ON tmp.MatchId = ml.MatchId
        WHERE mt.MatchId IS NULL AND
            (ms.MatchId IS NOT NULL AND mi.MatchId IS NOT NULL AND (m.IsLineUp = 1 AND ml.MatchId))
        ;
    ELSE
        INSERT INTO livescore_match_temporary(MatchId)
        SELECT DISTINCT tmp.MatchId
        FROM tmp_Match tmp
        LEFT JOIN livescore_match_temporary mt ON tmp.MatchId = mt.MatchId
        WHERE mt.MatchId IS NULL;
    END IF;  

END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Match_Temporary_Insert TO 'sabasports'@'%';