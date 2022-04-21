DROP PROCEDURE IF EXISTS Livescore_SyncMatch_SyncDeteledMatch;
DELIMITER //

CREATE DEFINER = 'sabasports'@'%' PROCEDURE Livescore_SyncMatch_SyncDeteledMatch(
    IN deletedMatchJson JSON
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220421
        Param: - matchListJson: in JSON format
						[{"matchId": 6227374}, {"matchId": 6227375}]
        Revisions: - 20220318@Oliver: init
    */
)
SQL SECURITY DEFINER
BEGIN
 # Variables
    SET errCode=0, errMsg='';
    
    DROP TEMPORARY TABLE IF EXISTS tmp_Match;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_Match(MatchId bigint PRIMARY KEY NOT NULL);
    
	INSERT INTO tmp_Match(MatchId)
    SELECT m.MatchId
    FROM 
		JSON_TABLE
        (
			deletedMatchJson,
            "$[*]"
            COLUMNS
            (
				MatchId bigint PATH "$.MatchId"
            )
        ) AS m;


    INSERT INTO livescore_match_deleted(MatchId)
    SELECT m.MatchId
    FROM tmp_Match m
    ON DUPLICATE KEY UPDATE
        MatchId = m.MatchId;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_SyncMatch_SyncDeteledMatch TO 'sabasports'@'%';