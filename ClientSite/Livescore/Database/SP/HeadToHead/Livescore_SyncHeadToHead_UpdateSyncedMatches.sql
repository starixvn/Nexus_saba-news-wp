DROP PROCEDURE IF EXISTS Livescore_SyncHeadToHead_UpdateSyncedMatches;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_SyncHeadToHead_UpdateSyncedMatches(
    matchIds TEXT
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220407
        Revisions: - 20220407@Oliver: init
        Param: 
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

        INSERT INTO livescore_match_extra(MatchId, IsSyncedHeadToHead)
        SELECT m.MatchId, 1
        FROM tmp_Match m
        ON DUPLICATE KEY UPDATE
            IsSyncedHeadToHead = 1;
	END IF;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_SyncHeadToHead_UpdateSyncedMatches TO 'sabasports'@'%';