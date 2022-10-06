DROP PROCEDURE IF EXISTS Livescore_League_GetLeagueById;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetLeagueById`(
	In LeagueId LONG
	/*
		Creator: Brian@20220729
        Revisions:
			- 20220729@Brian: init
            - 20220831@Hank: add current stage field
    */
)
BEGIN
	SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.CurrentSeasonId, l.Country as CountryEN, l.CountryVN, l.CurrentStageId
	FROM livescore_league l
	Where l.LeagueId = LeagueId;
END

//
DELIMITER ;	


DROP PROCEDURE IF EXISTS Livescore_League_GetSeasonStandings;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetSeasonStandings`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    /*
		Creator: Hank@20220811
        Revisions:
			- 20220811@Hank: init
    */
)
BEGIN
	DECLARE _teamId BIGINT DEFAULT(0);
	DECLARE _stageId BIGINT DEFAULT(0);
    
    DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT, HomeTeamId BIGINT, AwayTeamId BIGINT);
    
	DROP TEMPORARY TABLE IF EXISTS tmp_Standings;
    CREATE TEMPORARY TABLE tmp_Standings (TeamId BIGINT, StageId BIGINT);
    
    INSERT INTO tmp_Standings (TeamId, StageId)
    SELECT DISTINCT s.TeamId, s.StageId
    FROM livescore_season_standings s
	WHERE s.SeasonId = seasonId AND s.StageId > 0;
    
    SELECT t.TeamId, t.TeamNameVN, t.TeamNameEN, t.TeamFullNameEN, t.Icon, t.IconVN
		,s.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.OrderIndex
		,COALESCE(se.GroupName, s.GroupName) as GroupName, COALESCE(se.Total, s.Total) as Total, COALESCE(se.Won, s.Won) as Won
        ,COALESCE(se.Loss, s.Loss) as Loss, COALESCE(se.Draw, s.Draw) as Draw, COALESCE(se.Points, s.Points) as Points
        ,COALESCE(se.Goals, s.Goals) as Goals, COALESCE(se.GoalsAgainst, s.GoalsAgainst) as GoalsAgainst, COALESCE(se.GoalDiff, s.GoalDiff) as GoalDiff
        ,(IFNULL(se.Position, s.Position)) as Position,  s.Promotion
    FROM livescore_season_standings s
		INNER JOIN livescore_team t ON s.TeamId = t.TeamId
        INNER JOIN livescore_season_stage ss ON s.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_season_standings_extra se ON se.SeasonId = s.SeasonId AND se.StageId = s.StageId AND se.TeamId = s.TeamId
    WHERE s.SeasonId = seasonId AND s.StageId > 0
    ORDER BY ss.OrderIndex ASC, GroupName ASC, Position ASC;
END

//
DELIMITER ;	



DROP PROCEDURE IF EXISTS Livescore_League_GetSeasonStanding_Detail;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetSeasonStanding_Detail`(
    IN seasonId BIGINT
    ,IN stageId BIGINT
    ,IN teamId BIGINT
    /*
		Creator: Hank@20220811
        Revisions:
			- 20220811@Hank: init
    */
)
BEGIN
	
    SELECT t.TeamId, t.TeamNameVN, t.TeamNameEN, t.TeamFullNameEN, t.Icon, t.IconVN 
		,s.GroupName, se.GroupName as GroupNameCustom, s.Total, se.Total as TotalCustom, se.Won as WonCustom, s.Won
        ,s.Loss, se.Loss as LossCustom, s.Draw, se.Draw as DrawCustom, s.Points, se.Points as PointsCustom, s.Goals, se.Goals as GoalsCustom
        , s.GoalsAgainst, se.GoalsAgainst as GoalsAgainstCustom,  s.GoalDiff, se.GoalDiff as GoalDiffCustom
        ,(IFNULL(se.Position, s.Position)) as Position, s.Promotion
		,s.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.OrderIndex
    FROM livescore_season_standings s
		INNER JOIN livescore_team t ON s.TeamId = t.TeamId
        INNER JOIN livescore_season_stage ss ON s.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_season_standings_extra se ON se.SeasonId = s.SeasonId AND se.StageId = s.StageId AND se.TeamId = s.TeamId
    WHERE s.SeasonId = seasonId AND s.StageId = stageId AND s.TeamId = teamId;
END

//
DELIMITER ;	


DROP PROCEDURE IF EXISTS Livescore_Match_GetByIdUpdate;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetByIdUpdate`(
	matchId BIGINT, 
    IN sportId SMALLINT UNSIGNED
    /*
		Creator: Hank@20220920
        Revisions:
			- 20220920@Hank: init

    */
)
BEGIN
     SELECT m.MatchId, m.EndTime, m.Round
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamTotalScore, m.AwayTeamTotalScore
        ,mc.StartTime as StartTimeCustom, m.StartTime
        ,mc.Status as StatusCustom, m.Status, mc.Round as RoundCustom, m.Round
		,mc.HomeTeamHalfScore as HomeTeamHalfScoreCustom, m.HomeTeamHalfScore, mc.AwayTeamHalfScore as AwayTeamHalfScoreCustom, m.AwayTeamHalfScore
        ,mc.HomeTeamAggregateScore as HomeTeamAggregateScoreCustom, m.HomeTeamAggregateScore, mc.AwayTeamAggregateScore as AwayTeamAggregateScoreCustom, m.AwayTeamAggregateScore
        ,mc.HomeTeamPenaltyScore as HomeTeamPenaltyScoreCustom, sh.PenaltyScore as HomeTeamPenaltyScore, mc.AwayTeamPenaltyScore as AwayTeamPenaltyScoreCustom, sa.PenaltyScore as AwayTeamPenaltyScore
		,mc.HomeTeamExtraScore as HomeTeamExtraScoreCustom, sh.ExtraScore as HomeTeamExtraScore, mc.AwayTeamExtraScore as AwayTeamExtraScoreCustom, sa.ExtraScore as AwayTeamExtraScore
        ,m.LeagueId,  l.LeagueNameVN,  l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.CurrentSeasonId, l.Icon
        ,m.SeasonId
        ,ss.StageId, ss.StageNameEN, rr.NameVN AS StageNameVN, ss.Mode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.TeamNameVN, a.Icon, a.IconVN
    FROM livescore_match m
		INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
		INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
		INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
		LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
		LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
        LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
    WHERE m.MatchId = matchId AND m.SportId = sportId AND m.Status IS NOT NULL AND m.CombinedId IS NULL
		AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
END

//
DELIMITER ;	


DROP PROCEDURE IF EXISTS Livescore_Match_GetBySeason_Filter;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetBySeason_Filter`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    /*
		Creator: Hank@20220907
        Revisions:
			- 20220907@Hank: init
    */
)
BEGIN    
    
    
	DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT PRIMARY KEY);
    
    INSERT INTO tmp_MatchIds (MatchId)
    SELECT m.MatchId
    FROM livescore_match m
    WHERE m.LeagueId = leagueId
        AND IFNULL(m.SeasonId, 0) = seasonId
        AND m.CombinedId IS NULL
        AND m.Status IS NOT NULL
        AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
	
        
    SELECT m.MatchId, m.EndTime, m.GroupNum, m.RelatedId
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
        ,m.HomeTeamTotalScore, m.AwayTeamTotalScore
        ,COALESCE(mc.Round, m.Round) as Round
        ,COALESCE(mc.StartTime, m.StartTime) as StartTime
        ,COALESCE(mc.Status, m.Status) as Status
		,COALESCE(mc.HomeTeamHalfScore, m.HomeTeamHalfScore) as HomeTeamHalfScore, COALESCE(mc.AwayTeamHalfScore, m.AwayTeamHalfScore) as AwayTeamHalfScore
        ,COALESCE(mc.HomeTeamAggregateScore, m.HomeTeamAggregateScore) as HomeTeamAggregateScore, COALESCE(mc.AwayTeamAggregateScore, m.AwayTeamAggregateScore) as AwayTeamAggregateScore
        ,COALESCE(mc.HomeTeamPenaltyScore, sh.PenaltyScore) as HomeTeamPenaltyScore, COALESCE(mc.AwayTeamPenaltyScore, sa.PenaltyScore) as AwayTeamPenaltyScore
		,COALESCE(mc.HomeTeamExtraScore, sh.ExtraScore) as HomeTeamExtraScore, COALESCE(mc.AwayTeamExtraScore, sa.ExtraScore) as AwayTeamExtraScore
        ,m.HomeTeamId, h.TeamNameEN , h.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.TeamNameVN, a.Icon, a.IconVN
        ,m.StageId, ss.StageNameEN, COALESCE(ss.StageNameVN, rr.NameVN) AS StageNameVN, ss.Mode AS StageMode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
    FROM livescore_match m
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
		LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
    WHERE EXISTS (SELECT tmp_m.MatchId FROM tmp_MatchIds tmp_m WHERE m.MatchId = tmp_m.MatchId)
    ORDER BY StartTime ASC
		,CASE WHEN h.TeamNameVN IS NOT NULL THEN h.TeamNameVN
			WHEN h.TeamNameEN IS NOT NULL THEN h.TeamNameEN
			ELSE h.TeamFullNameEN END ASC;
            
	
END

//
DELIMITER ;	

DROP PROCEDURE IF EXISTS Livescore_Match_Update;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_Update`(
	IN matchId BIGINT,
    IN stageId BIGINT,
	IN homeTeamId BIGINT,
	IN homeTeamNameVN VARCHAR(255),
    IN homeTeamIconVN VARCHAR(255),
    IN awayTeamId BIGINT,
    IN awayTeamNameVN VARCHAR(255),
    IN awayTeamIconVN VARCHAR(255),
    IN stageNameVN VARCHAR(255),
    IN round SMALLINT,
    IN status SMALLINT,
    IN startTime DATETIME,
    IN homeTeamHalfScore SMALLINT,
    IN awayTeamHalfScore SMALLINT,
    IN homeTeamExtraScore SMALLINT,
    IN awayTeamExtraScore SMALLINT,
	IN homeTeamAggregateScore SMALLINT,
    IN awayTeamAggregateScore SMALLINT,
    IN homeTeamPenaltyScore SMALLINT,
    IN awayTeamPenaltyScore SMALLINT,
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)   
	/*
		Creator: Hank@20220907
        Revisions:
			- 20220907@Hank: init
    */
)
BEGIN
	UPDATE `livescore_team` as t
	SET
	`TeamNameVN` = homeTeamNameVN,
    `IconVN` = homeTeamIconVN
	WHERE t.TeamId = homeTeamId;
    
    UPDATE `livescore_team` as t
	SET
	`TeamNameVN` = awayTeamNameVN,
    `IconVN` = awayTeamIconVN
	WHERE t.TeamId = awayTeamId;
    
    UPDATE `livescore_season_stage` as ss
	SET
	`StageNameVN` = stageNameVN
	WHERE ss.stageId = stageId;
    
    INSERT INTO `livescore_match_custom`
	(`MatchId`,
    `Status`,
    `StartTime`,
    `Round`,
	`HomeTeamHalfScore`,
	`AwayTeamHalfScore`,
	`HomeTeamExtraScore`,
	`AwayTeamExtraScore`,
	`HomeTeamAggregateScore`,
	`AwayTeamAggregateScore`,
	`HomeTeamPenaltyScore`,
	`AwayTeamPenaltyScore`,
	`UpdatedDate`)
	VALUES
	(matchId,
    status,
    startTime,
    round,
	homeTeamHalfScore,
	awayTeamHalfScore,
	homeTeamExtraScore,
	awayTeamExtraScore,
	homeTeamAggregateScore,
	awayTeamAggregateScore,
	homeTeamPenaltyScore,
	awayTeamPenaltyScore,
	NOW())
    ON DUPLICATE KEY UPDATE 
    Status = status,
    StartTime = startTime,
    Round = round,
    HomeTeamHalfScore = homeTeamHalfScore,
    AwayTeamHalfScore = awayTeamHalfScore,
    HomeTeamExtraScore = homeTeamExtraScore,
    AwayTeamExtraScore = awayTeamExtraScore,
    HomeTeamAggregateScore = homeTeamAggregateScore,
    AwayTeamAggregateScore = awayTeamAggregateScore,
    HomeTeamPenaltyScore = homeTeamPenaltyScore,
    AwayTeamPenaltyScore = awayTeamPenaltyScore,
    UpdatedDate = NOW();

    
END

//
DELIMITER ;	

DROP PROCEDURE IF EXISTS Livescore_Season_GetByLeague;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Season_GetByLeague`(
	leagueId BIGINT
	/*
		Creator: Hank@20220907
        Revisions:
			- 20220907@Hank: init
    */
)
BEGIN
	SELECT s.LeagueId, s.SeasonId, s.StartTime, s.EndTime, s.Year
    FROM livescore_season s
    WHERE s.LeagueId = leagueId AND s.IsDeleted = 0
    ORDER BY s.SeasonId desc;
END

//
DELIMITER ;	


DROP PROCEDURE IF EXISTS Livescore_Standing_Update;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Standing_Update`(
	IN seasonId BIGINT,
    IN stageId BIGINT,
    IN teamId BIGINT,
    IN groupName VARCHAR(255),
    IN total SMALLINT,
    IN won SMALLINT,
    IN draw SMALLINT,
    IN loss SMALLINT,
    IN goals SMALLINT,
    IN goalsAgainst SMALLINT,
    IN goalDiff SMALLINT,
    IN points SMALLINT,
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)   
	/*
		Creator: Hank@20220907
        Revisions:
			- 20220907@Hank: init
    */
)
BEGIN
    INSERT INTO `sabasports`.`livescore_season_standings_extra`
	(`SeasonId`,
	`StageId`,
	`TeamId`,
    `GroupName`,
	`Total`,
	`Won`,
	`Loss`,
	`Draw`,
	`Goals`,
	`GoalsAgainst`,
	`GoalDiff`,
	`Points`,
	`UpdatedDate`)
	VALUES
	(seasonId,
	stageId,
	teamId,
    groupName,
	total,
	won,
	loss,
	draw,
	goals,
	goalsAgainst,
	goalDiff,
	points,
	NOW())
    ON DUPLICATE KEY UPDATE 
    GroupName = groupName,
    Total = total,
    Won = won,
    Loss = loss,
    Draw = draw,
    Goals = goals,
    GoalsAgainst = goalsAgainst,
    GoalDiff = goalDiff,
    Points = points,
    UpdatedDate = NOW();

    
    
END

//
DELIMITER ;	

DROP PROCEDURE IF EXISTS Livescore_Standing_UpdatePosition;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Standing_UpdatePosition`(
	IN seasonId INT,
    IN stageId INT,
    IN teamId INT,
    IN position INT,
    IN oldGroupName VARCHAR(255),
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100) 
	/*
		Creator: Hank@20220912
        Revisions:
			- 20220912@Hank: init
    */
)
BEGIN
	DECLARE currentGroupName VARCHAR(255);
    DECLARE tmpSeasonId BIGINT;
    DECLARE tmpStageId BIGINT;
    DECLARE tmpTeamId BIGINT;
    DECLARE currentPosition SMALLINT;
    SET currentPosition = 0;
	DROP TEMPORARY TABLE IF EXISTS tmp_standingExtra;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_standingExtra(
		SeasonId BIGINT NOT NULL, 
		StageId  BIGINT NOT NULL,
		TeamId BIGINT NOT NULL, 
		Position SMALLINT);
	
    SELECT COALESCE(se.GroupName, ss.GroupName) INTO currentGroupName
    FROM livescore_season_standings ss 
    LEFT JOIN livescore_season_standings_extra se ON se.SeasonId = ss.SeasonId AND se.StageId = ss.StageId AND se.TeamId = ss.TeamId
    WHERE ss.SeasonId = seasonId AND ss.StageId = stageId AND ss.TeamId = teamId;
    
    
    INSERT INTO tmp_standingExtra(SeasonId, StageId, TeamId, Position)
	SELECT seasonId, stageId, ss.TeamId, COALESCE(se.Position, ss.Position) as PositionTmp
    FROM livescore_season_standings ss
    LEFT JOIN livescore_season_standings_extra se ON se.SeasonId = ss.SeasonId AND se.StageId = ss.StageId AND se.TeamId = ss.TeamId
    WHERE ss.SeasonId = seasonId 
		AND ss.StageId = stageId 
        AND ss.TeamId != teamId
        AND ((se.GroupName is null AND ss.GroupName = currentGroupName) OR (se.GroupName is not null AND se.GroupName = currentGroupName))
	ORDER BY PositionTmp ASC;
    
	SET currentPosition = 0;
    
    
    WHILE (EXISTS (SELECT 1 FROM tmp_standingExtra)) DO
		SELECT ss.SeasonId, ss.StageId, ss.TeamId INTO tmpSeasonId, tmpStageId, tmpTeamId
		FROM tmp_standingExtra ss LIMIT 1;
		
        SET currentPosition = currentPosition + 1;
        IF currentPosition = position THEN
			SET currentPosition = currentPosition + 1;
		END IF;
        
        INSERT INTO `sabasports`.`livescore_season_standings_extra`
		(`SeasonId`,
		`StageId`,
		`TeamId`,
		`Position`,
		`UpdatedDate`)
		VALUES
		(tmpSeasonId,
		tmpStageId,
		tmpTeamId,
		currentPosition,
		now())
        ON DUPLICATE KEY UPDATE Position=currentPosition;
		
        DELETE FROM tmp_standingExtra as tp WHERE tp.SeasonId = tmpSeasonId AND tp.StageId = tmpStageId AND tp.TeamId = tmpTeamId;
    END WHILE;
    
    INSERT INTO `sabasports`.`livescore_season_standings_extra`
		(`SeasonId`,
		`StageId`,
		`TeamId`,
		`Position`,
		`UpdatedDate`)
		VALUES
		(seasonId,
		stageId,
		teamId,
		position,
		now())
        ON DUPLICATE KEY UPDATE Position = position;
        
	IF oldGroupName != currentGroupName THEN
		TRUNCATE tmp_standingExtra;
		INSERT INTO tmp_standingExtra(SeasonId, StageId, TeamId, Position)
		SELECT seasonId, stageId, ss.TeamId, COALESCE(se.Position, ss.Position) as PositionTmp
		FROM livescore_season_standings ss
		LEFT JOIN livescore_season_standings_extra se ON se.SeasonId = ss.SeasonId AND se.StageId = ss.StageId AND se.TeamId = ss.TeamId
		WHERE ss.SeasonId = seasonId 
			AND ss.StageId = stageId 
			AND ss.TeamId != teamId
			AND ((se.GroupName is null AND ss.GroupName = oldGroupName) OR (se.GroupName is not null AND se.GroupName = oldGroupName))
		ORDER BY PositionTmp ASC;
        
        SET currentPosition = 0;
        
        WHILE (EXISTS (SELECT 1 FROM tmp_standingExtra)) DO
			SELECT ss.SeasonId, ss.StageId, ss.TeamId INTO tmpSeasonId, tmpStageId, tmpTeamId
			FROM tmp_standingExtra ss LIMIT 1;
			
			SET currentPosition = currentPosition + 1;
			
			INSERT INTO `sabasports`.`livescore_season_standings_extra`
			(`SeasonId`,
			`StageId`,
			`TeamId`,
			`Position`,
			`UpdatedDate`)
			VALUES
			(tmpSeasonId,
			tmpStageId,
			tmpTeamId,
			currentPosition,
			now())
			ON DUPLICATE KEY UPDATE Position=currentPosition;
			
			DELETE FROM tmp_standingExtra as tp WHERE tp.SeasonId = tmpSeasonId AND tp.StageId = tmpStageId AND tp.TeamId = tmpTeamId;
		END WHILE;
    END IF;
    
    
    
END

//
DELIMITER ;