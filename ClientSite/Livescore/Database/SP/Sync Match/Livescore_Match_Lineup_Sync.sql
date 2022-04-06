DROP PROCEDURE IF EXISTS Livescore_Match_Lineup_Sync;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_Match_Lineup_Sync(
    IN matchLineupJson JSON
    ,IN lineupPlayerJson JSON
    ,OUT errCode SMALLINT
	,OUT errMsg VARCHAR(100)
    /*
		Creator: Oliver@20220325
        Param: - matchLineupJson: in JSON format
						[{
                            "MatchId": 6227374,
                            "HomeFormation": "4-3-3",
                            "AwayFormation": "3-3-2-2",
                        }]
                - lineupPlayerJson: in JSON format
                    [{
                        "PlayerId": 5924747,
                        "Position": 1,
                        "PlayerName": "Player name",
                        "PlayerNumber": 10,
                        "IsHomeTeam": true,
                        "IsSubstitute": false,
                        "CoordinateX": 48,
                        "CoordinateY": 69
                    }]
        Revisions: - 20220325@Oliver: init
    */
)
SQL SECURITY DEFINER
BEGIN
 # Variables
    SET errCode=0, errMsg='';
    
    DROP TEMPORARY TABLE IF EXISTS tmp_Lineup;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_Lineup(
                        MatchId BIGINT PRIMARY KEY NOT NULL,
                        HomeFormation VARCHAR(50),
                        AwayFormation VARCHAR(50));
    
	INSERT INTO tmp_Lineup(MatchId, HomeFormation, AwayFormation)
    SELECT m.MatchId, m.HomeFormation, m.AwayFormation
    FROM 
		JSON_TABLE
        (
			matchLineupJson,
            "$[*]"
            COLUMNS
            (
				MatchId bigint PATH "$.MatchId",
                HomeFormation VARCHAR(30) PATH "$.HomeFormation",
                AwayFormation VARCHAR(30) PATH "$.AwayFormation"
            )
        ) AS m;

    INSERT INTO livescore_lineup(MatchId, HomeFormation, AwayFormation, SyncedDate)
    SELECT tl.MatchId, tl.HomeFormation, tl.AwayFormation, NOW()
    FROM tmp_Lineup tl
    ON DUPLICATE KEY UPDATE
        HomeFormation = tl.HomeFormation,
        AwayFormation = tl.AwayFormation;

/* End handle update score------------------ */

    DROP TEMPORARY TABLE IF EXISTS tmp_playerLineup;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_playerLineup(
                        MatchId BIGINT NOT NULL,
                        TeamId BIGINT NOT NULL,
                        PlayerId BIGINT NOT NULL,
                        PlayerName VARCHAR(100),
                        Position INT NOT NULL,
                        PlayerNumber INT NOT NULL,
                        IsSubstitute BIT,
                        CoordinateX INT NOT NULL,
                        CoordinateY INT NOT NULL);
    
	INSERT INTO tmp_playerLineup(MatchId, TeamId, PlayerId, PlayerName, Position, PlayerNumber, IsSubstitute, CoordinateX, CoordinateY)
    SELECT tpl.MatchId 
           ,(CASE WHEN tpl.IsHomeTeam = 1
                THEN m.HomeTeamId
                ELSE m.AwayTeamId
            END) As TeamId 
           ,tpl.PlayerId 
           ,''
           ,tpl.Position 
           ,tpl.PlayerNumber 
           ,tpl.IsSubstitute 
           ,tpl.CoordinateX 
           ,tpl.CoordinateY 
    FROM JSON_TABLE
        (
			lineupPlayerJson,
            "$[*]"
            COLUMNS
            (
				MatchId BIGINT PATH "$.MatchId",
                PlayerId BIGINT PATH "$.PlayerId",
                PlayerName VARCHAR(100) PATH "$.PlayerName",
                Position INT PATH "$.Position",
                PlayerNumber INT PATH "$.PlayerNumber",
                IsHomeTeam TINYINT(4) PATH "$.IsHomeTeam",
                IsSubstitute TINYINT(4) PATH "$.IsSubstitute",
                CoordinateX INT PATH "$.CoordinateX",
                CoordinateY INT PATH "$.CoordinateY"
            )
        ) AS tpl
    INNER JOIN livescore_match m ON tpl.MatchId = m.MatchId;
    
    DELETE FROM livescore_lineup_detail tlu
    WHERE tlu.MatchId IN (SELECT DISTINCT MatchId FROM tmp_playerLineup);

    INSERT INTO livescore_lineup_detail(MatchId, TeamId, PlayerId, PlayerName, IsSubstitute, PlayerNumber, Position, CoordinateX, CoordinateY)
    SELECT tpl.MatchId
           ,tpl.TeamId    
           ,tpl.PlayerId
           ,p.NameEN
           ,tpl.IsSubstitute
           ,tpl.PlayerNumber
           ,tpl.Position
           ,tpl.CoordinateX
           ,tpl.CoordinateY
    FROM tmp_playerLineup AS tpl
    LEFT JOIN livescore_player p ON tpl.PlayerId = p.PlayerId 
        AND p.TeamId = tpl.TeamId;
        
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_Match_Lineup_Sync TO 'sabasports'@'%';