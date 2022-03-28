DROP PROCEDURE IF EXISTS Livescore_Match_TemporaryMatchIds_Insert;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_Match_TemporaryMatchIds_Insert(
    IN matchIds JSON
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220324
        Revisions: - 20220324@Oliver: Insert temporary Match Ids & IsLineUp
        Params: - matchIds: JSON format
                [{
                    "MatchId": 6249362,
                    "IsLineup": 1
                }]
    */
)
SQL SECURITY DEFINER
BEGIN
    SET errCode=0, errMsg='';
    
    DROP TEMPORARY TABLE IF EXISTS tmp_Match;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_Match(MatchId bigint PRIMARY KEY NOT NULL, IsLineUp SMALLINT);

    INSERT INTO tmp_Match(MatchId, IsLineup)
    SELECT m.MatchId, m.IsLineup
    FROM 
		JSON_TABLE
        (
			matchListJson,
            "$[*]"
            COLUMNS
            (
				MatchId bigint PATH "$.MatchId",
                IsLineUp bit PATH "$.IsLineUp"
            )
        ) AS m;

    INSERT INTO livescore_temporary_match(MatchId, IsLineup)
    SELECT m.MatchId, m.IsLineup
    FROM tmp_Match m
    ON DUPLICATE KEY UPDATE
        IsLineup = m.IsLineup;

END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Match_TemporaryMatchIds_Insert TO 'sabasports'@'%';