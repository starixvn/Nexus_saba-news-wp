DROP PROCEDURE IF EXISTS Livescore_Match_Sync;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_Match_Sync(
    IN matchListJson JSON
    /*
		Creator: Oliver@20220318
        Param: - matchListJson: in JSON format
						[{"matchId": 6227374,
                        "combinedId": null,
                        "SportId": 1,
                        "leagueId": 52689,
                        "seasonId": 10538,
                        "stageId": 30352,
                        "groupNum": 0,
                        "round": 0,
                        "neutral": 0,
                        "gameStartTime": "2022-03-17T16:00:00.000Z",
                        "gameEndTime": "2022-03-17T18:25:00.000Z",
                        "updatedAt": "2022-03-18T04:23:51.000Z",
                        "HomeTeamId": 38946,
                        "AwayTeamId": 39545,
                        "venueId": null,
                        "relatedId": null,
                        "isLineUp": 1,
                        "isIntelligence": 0,
                        "gameType": 50,
                        "footballAddStartTime": null,
                        "footballStartTime": "2022-03-17T16:01:52.000Z",
                        "footballNextHalfStartTime": "2022-03-17T17:01:55.000Z"}]
        Revisions: - 20220318@Oliver: init
    */
)
SQL SECURITY DEFINER
BEGIN
 # Variables
	DECLARE iLoop INT;
    
    DROP TEMPORARY TABLE IF EXISTS tmp_Match;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_Match(MatchId bigint PRIMARY KEY NOT NULL,
                                                    CombinedId bigint,
                                                    SportId tinyint DEFAULT 1,
                                                    LeagueId bigint NOT NULL,
                                                    SeasonId bigint,
                                                    RoundId bigint,
                                                    StageId bigint,
                                                    GroupNum int,
                                                    RelatedId bigint,
                                                    Round int,
                                                    Neutral int,
                                                    StartTime datetime,
                                                    EndTime datetime,
                                                    HomeTeamId bigint NOT NULL,
                                                    AwayTeamId bigint NOT NULL,
                                                    Status int NOT NULL,
                                                    IsLineUp bit,
                                                    IsIntelligence bit,
                                                    UpdatedAt datetime DEFAULT NULL);
    
	INSERT INTO tmp_Match(MatchId,
                        CombinedId,
                        SportId,
                        LeagueId,
                        SeasonId,
                        RoundId,
                        StageId,
                        GroupNum,
                        RelatedId,
                        Round,
                        Neutral,
                        StartTime,
                        EndTime,
                        HomeTeamId,
                        AwayTeamId,
                        Status,
                        IsLineUp,
                        IsIntelligence,
                        UpdatedAt)
    SELECT m.MatchId,
            m.CombinedId,
            m.SportId,
            m.LeagueId,
            m.SeasonId,
            m.RoundId,
            m.StageId,
            m.GroupNum,
            m.RelatedId,
            m.Round,
            m.Neutral,
            m.StartTime,
            m.EndTime,
            m.HomeTeamId,
            m.AwayTeamId,
            m.Status,
            m.IsLineUp,
            m.IsIntelligence,
            m.UpdatedAt
    FROM 
		JSON_TABLE
        (
			matchListJson,
            "$[*]"
            COLUMNS
            (
				MatchId bigint PATH "$.MatchId",
                CombinedId bigint PATH "$.CombinedId",
                SportId tinyint PATH "$.SportId",
                LeagueId bigint PATH "$.LeagueId",
                SeasonId bigint PATH "$.SeasonId",
                RoundId bigint PATH "$.RoundId",
                StageId bigint PATH "$.StageId",
                GroupNum int PATH "$.GroupNum",
                RelatedId bigint PATH "$.RelatedId",
                Round int PATH "$.Round",
                Neutral int PATH "$.Neutral",
                StartTime datetime PATH "$.StartTime",
                EndTime datetime PATH "$.EndTime",
                HomeTeamId bigint PATH "$.HomeTeamId",
                AwayTeamId bigint PATH "$.AwayTeamId",
                Status int PATH "$.Status",
                IsLineUp bit PATH "$.IsLineUp",
                IsIntelligence bit PATH "$.IsIntelligence",
                UpdatedAt datetime PATH "$.UpdatedAt"
            )
        ) AS m;

        INSERT INTO livescore_match(MatchId,
                        CombinedId,
                        SportId,
                        LeagueId,
                        SeasonId,
                        RoundId,
                        StageId,
                        GroupNum,
                        RelatedId,
                        Round,
                        Neutral,
                        StartTime,
                        EndTime,
                        HomeTeamId,
                        AwayTeamId,
                        Status,
                        IsLineUp,
                        IsIntelligence,
                        SyncedDate,
                        UpdatedAt)
        SELECT m.MatchId,
                m.CombinedId,
                m.SportId,
                m.LeagueId,
                m.SeasonId,
                m.RoundId,
                m.StageId,
                m.GroupNum,
                m.RelatedId,
                m.Round,
                m.Neutral,
                m.StartTime,
                m.EndTime,
                m.HomeTeamId,
                m.AwayTeamId,
                m.Status,
                m.IsLineUp,
                m.IsIntelligence,
                NOW(),
                m.UpdatedAt
        FROM tmp_Match m
        ON DUPLICATE KEY UPDATE
            LeagueId = m.LeagueId,
            SeasonId = m.SeasonId,
            RoundId = m.SeasonId,
            StageId = m.StageId,
            GroupNum = m.GroupNum,
            RelatedI = m.RelatedI,
            Round = m.Round,
            Neutral = m.Neutral,
            StartTime = m.StartTime,
            EndTime = m.EndTime,
            HomeTeamId = m.HomeTeamId,
            AwayTeamId = m.AwayTeamId,
            Status = m.Status,
            IsLineUp = m.IsLineUp,
            IsIntelligence = m.IsIntelligence,
            UpdatedAt = m.UpdatedAt;

	
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Match_Sync TO 'sabasports'@'%';