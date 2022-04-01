DROP PROCEDURE IF EXISTS Livescore_Sync_HeadToHead;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_Sync_HeadToHead(
    IN headToHeadJson JSON
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220325
        Param: - incidentJson: in JSON format
						[{
                            "MatchId": 6227374,
                            "HomeTeamId": 52733,
                            "AwayTeamId": 58293,
                            "TotalHomeScore": 1,
                            "TotalAwayScore": 0,
                            "HalfHomeScore": 0,
                            "HalfAwayScore": 0,
                        }]
        Revisions: - 20220328@Oliver: init
    */
)
SQL SECURITY DEFINER
BEGIN
 # Variables
    SET errCode=0, errMsg='';
    
    DROP TEMPORARY TABLE IF EXISTS tmp_match;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_match(
                        MatchId BIGINT NOT NULL,
                        HomeTeamId BIGINT NOT NULL,
                        AwayTeamId BIGINT NOT NULL,
                        TotalHomeScore SMALLINT,
                        TotalAwayScore SMALLINT,
                        HalfHomeScore SMALLINT,
                        HalfAwayScore SMALLINT);
    
	INSERT INTO tmp_match(
        MatchId,
        HomeTeamId,
        AwayTeamId,
        TotalHomeScore,
        TotalAwayScore,
        HalfHomeScore,
        HalfAwayScore)
    SELECT  m.MatchId,
            m.HomeTeamId,
            m.AwayTeamId,
            m.TotalHomeScore,
            m.TotalAwayScore,
            m.HalfHomeScore,
            m.HalfAwayScore
    FROM 
		JSON_TABLE
        (
			headToHeadJson,
            "$[*]"
            COLUMNS
            (
                MatchId BIGINT PATH "$.MatchId",
                HomeTeamId BIGINT PATH "$.HomeTeamId",
                AwayTeamId BIGINT PATH "$.AwayTeamId",
                TotalHomeScore SMALLINT PATH "$.TotalHomeScore",
                TotalAwayScore SMALLINT PATH "$.TotalAwayScore",
                HalfHomeScore SMALLINT PATH "$.HalfHomeScore",
                HalfAwayScore SMALLINT PATH "$.HalfAwayScore"
            )
        ) AS m
        LEFT JOIN livescore_match lm ON m.MatchId = lm.MatchId
        WHERE lm.MatchId = NULL;

    INSERT INTO livescore_match(
        MatchId,
        HomeTeamId,
        AwayTeamId,
        HomeTeamHalfScore,
        AwayTeamHalfScore,
        HomeTeamTotalScore,
        AwayTeamTotalScore)
    SELECT m.MatchId,
            m.HomeTeamId,
            m.AwayTeamId,
            m.TotalHomeScore,
            m.TotalAwayScore,
            m.HalfHomeScore,
            m.HalfAwayScore
    FROM tmp_match m;
        
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Sync_HeadToHead TO 'sabasports'@'%';