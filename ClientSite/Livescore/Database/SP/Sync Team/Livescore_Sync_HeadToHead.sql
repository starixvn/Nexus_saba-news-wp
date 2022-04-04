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
    
    DROP TEMPORARY TABLE IF EXISTS tmp_h2h;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_h2h(
                        MatchId BIGINT NOT NULL,
                        HomeTeamId BIGINT NOT NULL,
                        AwayTeamId BIGINT NOT NULL,
                        TotalHomeScore SMALLINT,
                        TotalAwayScore SMALLINT,
                        HalfHomeScore SMALLINT,
                        HalfAwayScore SMALLINT);
    
	INSERT INTO tmp_h2h(
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
    SELECT h.MatchId,
            h.HomeTeamId,
            h.AwayTeamId,
            h.HalfHomeScore,
            h.HalfAwayScore,
            h.TotalHomeScore,
            h.TotalAwayScore,
            m.StartTime
    FROM tmp_h2h h
    INNER JOIN livescore_match m ON h.MatchId = m.MatchId
    ON DUPLICATE KEY UPDATE
            HomeHalfScore = h.HomeHalfScore,
            AwayHalfScore = h.AwayHalfScore,
            HomeTotalScore = h.HomeTotalScore,
            AwayTotalScore = h.AwayTotalScore
            EventDate = m.StartTime;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Sync_HeadToHead TO 'sabasports'@'%';