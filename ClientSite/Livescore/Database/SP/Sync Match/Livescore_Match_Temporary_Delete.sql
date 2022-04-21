DROP PROCEDURE IF EXISTS Livescore_Match_Temporary_Delete;
DELIMITER //

CREATE DEFINER = 'sabasports'@'%' PROCEDURE Livescore_Match_Temporary_Delete(
    IN matchIds TEXT
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220324
        Revisions: - 20220324@Oliver: Delete temporary Match Ids
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

    DELETE m 
    FROM livescore_match_temporary m
    INNER JOIN tmp_Match tmp ON m.MatchId = tmp.MatchId;

END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Match_Temporary_Delete TO 'sabasports'@'%';