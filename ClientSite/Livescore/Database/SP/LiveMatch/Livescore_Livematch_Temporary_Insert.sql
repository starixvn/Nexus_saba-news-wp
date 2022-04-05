DROP PROCEDURE IF EXISTS Livescore_Livematch_Temporary_Insert;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_Livematch_Temporary_Insert(
    IN matchIds TEXT
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220405
        Revisions: - 20220405@Oliver: Insert temporary Live match
        Params: - matchIds: "65723,23232,242123"
    */
)
SQL SECURITY DEFINER
BEGIN
    SET errCode=0, errMsg='';
    
    TRUNCATE TABLE livescore_match_live;

    DROP TEMPORARY TABLE IF EXISTS tmp_Match;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_Match(MatchId bigint PRIMARY KEY NOT NULL);

    IF IFNULL(matchIds,'') <> ''THEN
		SET @sqlCommand = REPLACE(matchIds, ',', '),(');
		SET @sqlCommand = CONCAT('(', @sqlCommand, ')');
		SET @sqlCommand = CONCAT('INSERT INTO tmp_Match VALUES', @sqlCommand); 
                                                            
		PREPARE stmt FROM  @sqlCommand;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;

        INSERT INTO livescore_match_live(MatchId)
        SELECT m.MatchId
        FROM tmp_Match m;
	END IF;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Livematch_Temporary_Insert TO 'sabasports'@'%';