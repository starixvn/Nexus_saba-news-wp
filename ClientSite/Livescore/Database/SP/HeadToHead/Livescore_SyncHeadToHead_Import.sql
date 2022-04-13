DROP PROCEDURE IF EXISTS Livescore_SyncHeadToHead_Import;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_SyncHeadToHead_Import(
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
                            "HomeTotalScore": 1,
                            "AwayTotalScore": 0,
                            "HomeHalfScore": 0,
                            "AwayHalfScore": 0,
                        }]
        Revisions: - 20220328@Oliver: init
    */
)
SQL SECURITY DEFINER
BEGIN
 # Variables
    SET errCode=0, errMsg='';
    
    DROP TEMPORARY TABLE IF EXISTS tmp_h2h;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_h2h(
                        MatchId BIGINT NOT NULL,
                        HomeTeamId BIGINT NOT NULL,
                        AwayTeamId BIGINT NOT NULL,
                        HomeTotalScore SMALLINT,
                        AwayTotalScore SMALLINT,
                        HomeHalfScore SMALLINT,
                        AwayHalfScore SMALLINT);
    
	INSERT INTO tmp_h2h(
        MatchId,
        HomeTeamId,
        AwayTeamId,
        HomeTotalScore,
        AwayTotalScore,
        HomeHalfScore,
        AwayHalfScore)
    SELECT  m.MatchId,
            m.HomeTeamId,
            m.AwayTeamId,
            m.HomeTotalScore,
            m.AwayTotalScore,
            m.HomeHalfScore,
            m.AwayHalfScore
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
                HomeTotalScore SMALLINT PATH "$.HomeTotalScore",
                AwayTotalScore SMALLINT PATH "$.AwayTotalScore",
                HomeHalfScore SMALLINT PATH "$.HomeHalfScore",
                AwayHalfScore SMALLINT PATH "$.AwayHalfScore"
            )
        ) AS m;

    INSERT INTO livescore_head_to_head(
        MatchId,
        HomeTeamId,
        AwayTeamId,
        HomeHalfScore,
        AwayHalfScore,
        HomeTotalScore,
        AwayTotalScore,
        EventDate)
    SELECT DISTINCT h.MatchId,
            h.HomeTeamId,
            h.AwayTeamId,
            h.HomeHalfScore,
            h.AwayHalfScore,
            h.HomeTotalScore,
            h.AwayTotalScore,
            m.StartTime
    FROM tmp_h2h h
    LEFT JOIN livescore_match m ON h.MatchId = m.MatchId
    ON DUPLICATE KEY UPDATE
            HomeHalfScore = h.HomeHalfScore,
            AwayHalfScore = h.AwayHalfScore,
            HomeTotalScore = h.HomeTotalScore,
            AwayTotalScore = h.AwayTotalScore,
            EventDate = m.StartTime;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_SyncHeadToHead_Import TO 'sabasports'@'%';