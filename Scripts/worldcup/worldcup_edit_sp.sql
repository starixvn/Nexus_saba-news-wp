DROP PROCEDURE IF EXISTS get_posts_by_cate_slug;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts_by_cate_slug`(
	_categorySlug VARCHAR(200),
    postType VARCHAR(200),
    _start INT,
    _end INT,
    OUT TotalItems int
	 /*
		Creator: Hank@20210720
        Revisions:
			- 20210720@Hank: init
            - 20221001@Hank: get sub cate from function & filter by post type
    */
)
BEGIN
	DECLARE count  INT;
	DECLARE category INT;
    DECLARE cate_name VARCHAR(200);
    DECLARE cate_parent_name VARCHAR(200);
    DECLARE cate_parent INT;
    DECLARE result JSON;
    
    DROP TEMPORARY TABLE IF EXISTS tmp_PostIds;
    CREATE TEMPORARY TABLE tmp_PostIds (PostId BIGINT);
    
    DROP TEMPORARY TABLE IF EXISTS tmp_Terms;
    CREATE TEMPORARY TABLE tmp_Terms (term_id BIGINT, slug VARCHAR(255), term_taxonomy_id BIGINT);
    
    SET result = get_sub_cate(_categorySlug);
    
	INSERT INTO tmp_Terms (term_id, slug, term_taxonomy_id)
    SELECT termid, slug, term_taxonomy_id
    FROM 
		JSON_TABLE
        (
			result,
            "$[*]"
            COLUMNS
            (
				termid BIGINT PATH "$.term_id"
				,slug VARCHAR(255) PATH "$.slug"
				,term_taxonomy_id BIGINT PATH "$.term_taxonomy_id"
            )
        ) AS t; 
        
    
    SELECT t.term_id, t.name, wtt.parent INTO category, cate_name, cate_parent 
    FROM wp_terms t INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND taxonomy = "category" WHERE slug = _categorySlug;
    
    
    INSERT INTO tmp_PostIds (PostId)
    SELECT p.ID
	FROM wp_posts as p
	WHERE p.post_type = postType AND p.post_status = 'publish'
	AND p.ID IN (SELECT tr.object_id from wp_term_relationships as tr 
					INNER JOIN tmp_Cate as c ON c.term_taxonomy_id = tr.term_taxonomy_id);
                    
	SELECT count(*) INTO TotalItems FROM tmp_PostIds;
	
	SELECT p.id as ID, p.post_title as PostTitle, p.post_content as PostContent, p.post_excerpt as PostExcerpt
    , p.post_date as PostDate, p.post_date_gmt as PostDateGMT, p.post_name as Slug
    , pp.guid as FeatureImage, m1.meta_value as Metadata, p.comment_count as CommentCount
    , cate_parent_name as CategoryParent, cate_name as Categories
	FROM wp_posts as p
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
	WHERE EXISTS (SELECT tmp.PostId FROM tmp_PostIds tmp WHERE tmp.PostId = p.ID)
	ORDER BY p.post_date DESC
	limit _start, _end;
	
END

//
DELIMITER ;	


DROP PROCEDURE IF EXISTS get_posts_by_slug;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts_by_slug`(
	categorySlug VARCHAR(200),
    postType VARCHAR(200),
    start INT,
    end INT
    /*
		Creator: Hank@20210720
        Revisions:
			- 20210720@Hank: init
            - 20220727@Hank: get sub cate posts
            - 20221001@Hank: get sub cate from function & filter by post type
    */
)
BEGIN
	DECLARE category INT;
	DECLARE cate_name VARCHAR(200);
    DECLARE result JSON;
    
    
	DROP TEMPORARY TABLE IF EXISTS tmp_Cates;
    CREATE TEMPORARY TABLE tmp_Cates (term_id BIGINT, slug VARCHAR(255), term_taxonomy_id BIGINT);
    
    SET result = get_sub_cate(categorySlug);
    
	INSERT INTO tmp_Cates (term_id, slug, term_taxonomy_id)
    SELECT termid, slug, term_taxonomy_id
    FROM 
		JSON_TABLE
        (
			result,
            "$[*]"
            COLUMNS
            (
				termid BIGINT PATH "$.term_id"
				,slug VARCHAR(255) PATH "$.slug"
				,term_taxonomy_id BIGINT PATH "$.term_taxonomy_id"
            )
        ) AS t; 
    
    
   
	SELECT p.id as ID, p.post_title as PostTitle, p.post_content as PostContent
    , p.post_date as PostDate, p.post_date_gmt as PostDateGMT, p.post_name as Slug
    , pp.guid as FeatureImage, m1.meta_value as Metadata
    , p.comment_count as CommentCount, p.post_excerpt as PostExcerpt
	FROM wp_posts as p
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
	WHERE p.post_type = postType AND p.post_status = 'publish'
	AND p.ID IN (SELECT tr.object_id from wp_term_relationships as tr 
					INNER JOIN tmp_Cates as c ON c.term_taxonomy_id = tr.term_taxonomy_id)
	ORDER BY p.post_date DESC
	limit start, end;
END

//
DELIMITER ;	


DROP PROCEDURE IF EXISTS Livescore_Match_GetByDate;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetByDate`(
	IN fromTime DATETIME
    ,IN toTime DATETIME
    ,IN sportId SMALLINT UNSIGNED
    /*
		Creator: Tim@20220322
        Revisions:
			- 20220322@Tim: init
			- 202200512@Tim: check 2nd leg
			- 202200517@Tim: get start time for live match
            - 20220711@Hank: get country data
            - 20220712@Hank: get only major league
            - 20220915@Hank: get Team Name & Icon VN
    */
)
BEGIN
	DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT PRIMARY KEY);
    
    INSERT INTO tmp_MatchIds (MatchId)
    SELECT m.MatchId
    FROM livescore_match m
        INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
    WHERE m.SportId = sportId
		AND m.Status IS NOT NULL
		AND COALESCE(mc.StartTime, m.StartTime) BETWEEN fromTime AND toTime
        AND m.CombinedId IS NULL
        AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
    
    SELECT m.MatchId, m.EndTime, m.GroupNum
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamTotalScore, m.AwayTeamTotalScore
        ,COALESCE(mc.Round, m.Round) as Round
        ,COALESCE(mc.StartTime, m.StartTime) as StartTime
        ,COALESCE(mc.Status, m.Status) as Status
		,COALESCE(mc.HomeTeamHalfScore, m.HomeTeamHalfScore) as HomeTeamHalfScore, COALESCE(mc.AwayTeamHalfScore, m.AwayTeamHalfScore) as AwayTeamHalfScore
        ,COALESCE(mc.HomeTeamAggregateScore, m.HomeTeamAggregateScore) as HomeTeamAggregateScore, COALESCE(mc.AwayTeamAggregateScore, m.AwayTeamAggregateScore) as AwayTeamAggregateScore
        ,COALESCE(mc.HomeTeamPenaltyScore, sh.PenaltyScore) as HomeTeamPenaltyScore, COALESCE(mc.AwayTeamPenaltyScore, sa.PenaltyScore) as AwayTeamPenaltyScore
		,COALESCE(mc.HomeTeamExtraScore, sh.ExtraScore) as HomeTeamExtraScore, COALESCE(mc.AwayTeamExtraScore, sa.ExtraScore) as AwayTeamExtraScore
        ,CASE WHEN m.Status = 10 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballStartTime END AS FootballStartTime
        ,CASE WHEN m.Status = 30 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballNextHalfStartTime END AS FootballNextHalfStartTime
        ,CASE WHEN m.Status = 40 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballAddStartTime END AS FootballAddStartTime
		,m.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, le.IsMajor, le.Priority, l.CountryVN, l.CountryIconVN
        ,c.CountryId, c.CountryLogo, c.CountryLogoCustom
        ,s.SeasonId, s.Year
        ,ss.StageId, ss.StageNameEN, COALESCE(ss.StageNameVN, rr.NameVN) AS StageNameVN, ss.Mode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.TeamNameVN, a.Icon, a.IconVN
    FROM livescore_match m
        INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        INNER JOIN livescore_league_extra le ON l.LeagueId = le.LeagueId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_season s ON m.SeasonId = s.SeasonId
        LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
        LEFT JOIN livescore_match_live live ON m.MatchId = live.MatchId
        LEFT JOIN livescore_country c ON c.CountryId = l.CountryId
        LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
	WHERE EXISTS (SELECT tmp_m.MatchId FROM tmp_MatchIds tmp_m WHERE m.MatchId = tmp_m.MatchId)
    ORDER BY CASE WHEN le.LeagueId IS NOT NULL THEN le.Priority ELSE 999999 END ASC
        ,CASE WHEN l.LeagueNameVN IS NOT NULL THEN l.LeagueNameVN
			WHEN l.LeagueNameEN IS NOT NULL THEN l.LeagueNameEN
            ELSE l.LeagueFullNameEN END ASC
		,StartTime ASC
        ,CASE WHEN h.TeamNameVN IS NOT NULL THEN h.TeamNameVN 
			WHEN h.TeamNameEN IS NOT NULL THEN h.TeamNameEN
			ELSE h.TeamFullNameEN END ASC;
END

//
DELIMITER ;


DROP PROCEDURE IF EXISTS Livescore_Match_GetById;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetById`(
	matchId BIGINT, 
    IN sportId SMALLINT UNSIGNED
    /*
		Creator: Hank@20220419
        Revisions:
			- 20220419@Hank: init
			- 202200512@Tim: check 2nd leg
			- 202200517@Tim: get start time for live match
            - 20220822@Hank add select season, round, stage
            - 20220915@Hank add Team Name & Icon VN & data custom
    */
)
BEGIN
     SELECT m.MatchId, m.EndTime, m.Round
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamTotalScore, m.AwayTeamTotalScore
        ,COALESCE(mc.Round, m.Round) as Round
        ,COALESCE(mc.StartTime, m.StartTime) as StartTime
        ,COALESCE(mc.Status, m.Status) as Status
		,COALESCE(mc.HomeTeamHalfScore, m.HomeTeamHalfScore) as HomeTeamHalfScore, COALESCE(mc.AwayTeamHalfScore, m.AwayTeamHalfScore) as AwayTeamHalfScore
        ,COALESCE(mc.HomeTeamAggregateScore, m.HomeTeamAggregateScore) as HomeTeamAggregateScore, COALESCE(mc.AwayTeamAggregateScore, m.AwayTeamAggregateScore) as AwayTeamAggregateScore
        ,COALESCE(mc.HomeTeamPenaltyScore, sh.PenaltyScore) as HomeTeamPenaltyScore, COALESCE(mc.AwayTeamPenaltyScore, sa.PenaltyScore) as AwayTeamPenaltyScore
		,COALESCE(mc.HomeTeamExtraScore, sh.ExtraScore) as HomeTeamExtraScore, COALESCE(mc.AwayTeamExtraScore, sa.ExtraScore) as AwayTeamExtraScore
        ,CASE WHEN m.Status = 10 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballStartTime END AS FootballStartTime
        ,CASE WHEN m.Status = 30 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballNextHalfStartTime END AS FootballNextHalfStartTime
        ,CASE WHEN m.Status = 40 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballAddStartTime END AS FootballAddStartTime
        ,m.LeagueId,  l.LeagueNameVN,  l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.CurrentSeasonId, l.Icon
        ,m.SeasonId
        ,ss.StageId, ss.StageNameEN, COALESCE(ss.StageNameVN, rr.NameVN) AS StageNameVN, ss.StageNameVN as StageNameCustom
        ,rr.NameVN as StageNameResource, ss.Mode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
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
        LEFT JOIN livescore_match_live live ON m.MatchId = live.MatchId
        LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
    WHERE m.MatchId = matchId AND m.SportId = sportId AND m.Status IS NOT NULL AND m.CombinedId IS NULL
		AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
END

//
DELIMITER ;


DROP PROCEDURE IF EXISTS Livescore_Match_GetByIds;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetByIds`(
	IN matchIds JSON
    /*
		Creator: Hank@20220419
        Revisions:
			- 20220419@Hank: init
			- 202200512@Tim: check 2nd leg
			- 202200517@Tim: get start time for live match
            - 20220711@Hank: get country data
            - 20220915@Hank: get team name & Icon VN & data custom
    */
)
BEGIN
	DROP TEMPORARY TABLE IF EXISTS tmp_ids;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_ids(MatchId BIGINT NOT NULL);
	
	INSERT INTO tmp_ids(MatchId)
    SELECT MatchId
    FROM 
		JSON_TABLE
        (
			matchIds,
            "$[*]"
            COLUMNS
            (
				MatchId BIGINT  PATH "$"
            )
        ) AS t;        
        
	SELECT m.MatchId, m.EndTime
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
        ,m.HomeTeamTotalScore, m.AwayTeamTotalScore
        ,COALESCE(mc.Round, m.Round) as Round
        ,COALESCE(mc.StartTime, m.StartTime) as StartTime
        ,COALESCE(mc.Status, m.Status) as Status
		,COALESCE(mc.HomeTeamHalfScore, m.HomeTeamHalfScore) as HomeTeamHalfScore, COALESCE(mc.AwayTeamHalfScore, m.AwayTeamHalfScore) as AwayTeamHalfScore
        ,COALESCE(mc.HomeTeamAggregateScore, m.HomeTeamAggregateScore) as HomeTeamAggregateScore, COALESCE(mc.AwayTeamAggregateScore, m.AwayTeamAggregateScore) as AwayTeamAggregateScore
        ,COALESCE(mc.HomeTeamPenaltyScore, sh.PenaltyScore) as HomeTeamPenaltyScore, COALESCE(mc.AwayTeamPenaltyScore, sa.PenaltyScore) as AwayTeamPenaltyScore
		,COALESCE(mc.HomeTeamExtraScore, sh.ExtraScore) as HomeTeamExtraScore, COALESCE(mc.AwayTeamExtraScore, sa.ExtraScore) as AwayTeamExtraScore
        ,CASE WHEN m.Status = 10 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballStartTime END AS FootballStartTime
        ,CASE WHEN m.Status = 30 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballNextHalfStartTime END AS FootballNextHalfStartTime
        ,CASE WHEN m.Status = 40 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballAddStartTime END AS FootballAddStartTime
        ,m.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.CurrentSeasonId, l.Icon, le.IsMajor, le.Priority, l.CountryVN
        ,c.CountryId, c.CountryLogo, c.CountryLogoCustom
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.TeamNameVN, a.Icon, a.IconVN
    FROM livescore_match m
		INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
		INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
		INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
		LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
		LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
		LEFT JOIN livescore_league_extra le ON l.LeagueId = le.LeagueId
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
        LEFT JOIN livescore_match_live live ON m.MatchId = live.MatchId
        LEFT JOIN livescore_country c ON c.CountryId = l.CountryId
        LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
    WHERE m.CombinedId IS NULL
			AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId)
            AND EXISTS (SELECT tmp.MatchId FROM tmp_ids tmp WHERE m.MatchId = tmp.MatchId)
     ORDER BY CASE WHEN le.LeagueId IS NOT NULL THEN le.Priority ELSE 9999999 END ASC
        ,CASE WHEN l.LeagueNameVN IS NOT NULL THEN l.LeagueNameVN
			WHEN l.LeagueNameEN IS NOT NULL THEN l.LeagueNameEN
            ELSE l.LeagueFullNameEN END ASC
		,StartTime ASC;
END

//
DELIMITER ;


DROP PROCEDURE IF EXISTS Livescore_Match_GetBySeason;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetBySeason`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    ,IN fromTime DATETIME
    ,IN toTime DATETIME
    /*
		Creator: Tim@20220412
        Revisions:
			- 20220412@Tim: init
			- 202200512@Tim: check 2nd leg
			- 202200517@Tim: get start time for live match
            - 202200610@Hank: check deleted match
            - 2022008100@Hank: add TeamNameVN & IconVN,  select season, data custom
    */
)
BEGIN    
    DECLARE _hasStandings BIT(1) DEFAULT 0;
    
	DROP TEMPORARY TABLE IF EXISTS tmp_MatchIds;
    CREATE TEMPORARY TABLE tmp_MatchIds (MatchId BIGINT PRIMARY KEY);
    
    SET _hasStandings = IF (EXISTS (SELECT 1 FROM livescore_season_standings ss WHERE ss.SeasonId = seasonId AND ss.StageId > 0), 1, 0);
    
    INSERT INTO tmp_MatchIds (MatchId)
    SELECT m.MatchId
    FROM livescore_match m
    WHERE m.LeagueId = leagueId
        AND IFNULL(m.SeasonId, 0) = seasonId
        AND m.CombinedId IS NULL
        AND m.Status IS NOT NULL
        AND (fromTime IS NULL OR m.StartTime BETWEEN fromTime AND toTime)
        AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);
	
    SELECT l.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, _hasStandings AS HasSeasonStandings, l.Icon, l.CurrentStageId
    FROM livescore_league l
	WHERE l.LeagueId = leagueId;
    
    SELECT s.SeasonId, s.StartTime, s.EndTime, Year FROM livescore_season s WHERE s.SeasonId = seasonId;
    
    SELECT m.MatchId, m.EndTime, m.GroupNum, m.RelatedId
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,COALESCE(mc.Round, m.Round) as Round
        ,COALESCE(mc.StartTime, m.StartTime) as StartTime
        ,COALESCE(mc.Status, m.Status) as Status
		,COALESCE(mc.HomeTeamHalfScore, m.HomeTeamHalfScore) as HomeTeamHalfScore, COALESCE(mc.AwayTeamHalfScore, m.AwayTeamHalfScore) as AwayTeamHalfScore
        ,COALESCE(mc.HomeTeamAggregateScore, m.HomeTeamAggregateScore) as HomeTeamAggregateScore, COALESCE(mc.AwayTeamAggregateScore, m.AwayTeamAggregateScore) as AwayTeamAggregateScore
        ,COALESCE(mc.HomeTeamPenaltyScore, sh.PenaltyScore) as HomeTeamPenaltyScore, COALESCE(mc.AwayTeamPenaltyScore, sa.PenaltyScore) as AwayTeamPenaltyScore
		,COALESCE(mc.HomeTeamExtraScore, sh.ExtraScore) as HomeTeamExtraScore, COALESCE(mc.AwayTeamExtraScore, sa.ExtraScore) as AwayTeamExtraScore
        ,CASE WHEN m.Status = 10 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballStartTime END AS FootballStartTime
        ,CASE WHEN m.Status = 30 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballNextHalfStartTime END AS FootballNextHalfStartTime
        ,CASE WHEN m.Status = 40 AND live.StartTime IS NOT NULL THEN live.StartTime ELSE m.FootballAddStartTime END AS FootballAddStartTime
        ,m.HomeTeamId, h.TeamNameVN as HomeTeamNameVN, h.TeamNameEN AS HomeTeamNameEN, h.TeamFullNameEN AS HomeTeamFullNameEN, h.Icon AS HomeTeamIcon, h.IconVN AS HomeTeamIconVN
        ,m.AwayTeamId, a.TeamNameVN as AwayTeamNameVN, a.TeamNameEN AS AwayTeamNameEN, a.TeamFullNameEN AS AwayTeamFullNameEN, a.Icon AS AwayTeamIcon, a.IconVN AS AwayTeamIconVN
        ,m.StageId, ss.StageNameEN, COALESCE(ss.StageNameVN, rr.NameVN) AS StageNameVN, ss.Mode AS StageMode, ss.GroupCount, ss.RoundCount, ss.OrderIndex
    FROM livescore_match m
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_ref_resource rr ON rr.RefName = 'Stage' AND ss.StageNameEN = rr.NameEN
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
        LEFT JOIN livescore_match_live live ON m.MatchId = live.MatchId
        LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
	WHERE EXISTS (SELECT tmp_m.MatchId FROM tmp_MatchIds tmp_m WHERE m.MatchId = tmp_m.MatchId)
    ORDER BY StartTime ASC
		,CASE WHEN h.TeamNameVN IS NOT NULL THEN h.TeamNameVN
			WHEN h.TeamNameEN IS NOT NULL THEN h.TeamNameEN
			ELSE h.TeamFullNameEN END ASC;
            
	
END

//
DELIMITER ;


DROP PROCEDURE IF EXISTS Livescore_Match_GetSeasonStandings;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetSeasonStandings`(
	IN leagueId BIGINT
    ,IN seasonId BIGINT
    /*
		Creator: Tim@20220418
        Revisions:
			- 20220418@Tim: init
			- 202200512@Tim: check 2nd leg
            - 202200615@Hank: check combine & delete match
            - 20220915@Hank: add team name & icon vn & update standing info
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
    
    SELECT t.TeamId, t.TeamNameVN, t.TeamNameEN, t.TeamFullNameEN, t.TeamNameVN, t.Icon, t.IconVN, s.GroupName
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
    
    
    lbltmpStandings: LOOP
		IF NOT EXISTS (SELECT 1 FROM tmp_Standings) THEN
			LEAVE lbltmpStandings;
		END IF;

		SELECT TeamId, StageId
        INTO _teamId, _stageId
        FROM tmp_Standings LIMIT 1;
        
        INSERT INTO tmp_MatchIds (MatchId, HomeTeamId, AwayTeamId)
		SELECT m.MatchId, m.HomeTeamId, m.AwayTeamId
		FROM livescore_match m
		WHERE m.Status = 50
			AND m.LeagueId = leagueId
			AND (m.HomeTeamId = _teamId OR m.AwayTeamId = _teamId)
			AND m.SeasonId = seasonId
			AND m.StageId = _stageId
            AND m.CombinedId IS NULL
            AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId)
		ORDER BY m.StartTime DESC
		LIMIT 5;

		DELETE FROM tmp_Standings WHERE TeamId = _teamId AND StageId = _stageId;
        
        ITERATE lbltmpStandings;
    END LOOP lbltmpStandings;
    
    SELECT m.MatchId, m.StartTime, m.EndTime, m.Status, m.GroupNum, m.Round
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
        ,sh.ExtraScore AS HomeTeamExtraScore, sa.ExtraScore AS AwayTeamExtraScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
        ,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN
        ,m.StageId, ss.StageNameEN
    FROM livescore_match m
        INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
        INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
        INNER JOIN livescore_season_stage ss ON m.StageId = ss.StageId
        LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
        LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
	WHERE EXISTS (SELECT tmp.MatchId FROM tmp_MatchIds tmp WHERE m.MatchId = tmp.MatchId)
    ORDER BY m.StartTime DESC;
END	

//
DELIMITER ;


DROP PROCEDURE IF EXISTS Livescore_Match_GetHeadToHead;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetHeadToHead`(
	matchId BIGINT
    /*
		Creator: Hank@20220419
        Revisions:
			- 20220419@Hank: init
			- 202200512@Tim: check 2nd leg
            - 20220916@Hank: select team name & icon vn
    */
)
BEGIN
    DECLARE homeTeamId BIGINT;
    DECLARE awayTeamId BIGINT;
    
    SELECT m.HomeTeamId, m.AwayTeamId INTO homeTeamId, awayTeamId
    FROM livescore_match m 
    WHERE m.MatchId = matchId;
    
    SELECT m.MatchId, m.EndTime, m.Status
		,CASE WHEN firstleg.MatchId IS NOT NULL THEN 1 ELSE 0 END AS IsSecondLeg
		,m.HomeTeamTotalScore, m.AwayTeamTotalScore
        ,COALESCE(mc.Round, m.Round) as Round
        ,COALESCE(mc.StartTime, m.StartTime) as StartTime
        ,COALESCE(mc.Status, m.Status) as Status
		,COALESCE(mc.HomeTeamHalfScore, m.HomeTeamHalfScore) as HomeTeamHalfScore, COALESCE(mc.AwayTeamHalfScore, m.AwayTeamHalfScore) as AwayTeamHalfScore
        ,COALESCE(mc.HomeTeamAggregateScore, m.HomeTeamAggregateScore) as HomeTeamAggregateScore, COALESCE(mc.AwayTeamAggregateScore, m.AwayTeamAggregateScore) as AwayTeamAggregateScore
        ,COALESCE(mc.HomeTeamPenaltyScore, sh.PenaltyScore) as HomeTeamPenaltyScore, COALESCE(mc.AwayTeamPenaltyScore, sa.PenaltyScore) as AwayTeamPenaltyScore
		,COALESCE(mc.HomeTeamExtraScore, sh.ExtraScore) as HomeTeamExtraScore, COALESCE(mc.AwayTeamExtraScore, sa.ExtraScore) as AwayTeamExtraScore
        ,m.LeagueId, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.CurrentSeasonId, l.Icon
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, h.TeamNameVN, h.Icon, h.IconVN
    FROM livescore_match m
		INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
		INNER JOIN livescore_team h ON h.TeamId = m.HomeTeamId
		INNER JOIN livescore_team a ON a.TeamId = m.AwayTeamId
		LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
		LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
        LEFT JOIN livescore_match firstleg ON m.RelatedId = firstleg.MatchId AND m.LeagueId = firstleg.LeagueId AND m.StartTime > firstleg.StartTime
        LEFT JOIN livescore_match_custom mc ON mc.MatchId = m.matchId
    WHERE m.MatchId IN (SELECT h2h.ReferenceMatchId FROM livescore_head_to_head h2h WHERE h2h.MatchId = matchId)
    ORDER BY StartTime DESC;
    
    SELECT homeTeamId AS homeId, awayTeamId AS awayId;
END
//
DELIMITER ;



DROP PROCEDURE IF EXISTS Livescore_League_Get;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_Get`(
	/*
		Creator: Hank@20220524
        Revisions:
			- 20220524@Hank: init
            - 20220712@Hank: get only major league
            - 20220808@Brian: get a few columns
    */
)
BEGIN
	SELECT l.LeagueId, l.CountryIconVN, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.CurrentSeasonId, l.Country as CountryEN, l.CountryVN, e.IsMajor, e.Priority, e.CreatedDate, c.CountryLogo
	FROM livescore_league l
		INNER JOIN livescore_league_extra e ON l.LeagueId = e.LeagueId
        LEFT JOIN livescore_country c ON c.CountryId = l.CountryId
	ORDER BY e.Priority is null, e.Priority;
END


//
DELIMITER ;	




DROP PROCEDURE IF EXISTS Livescore_League_GetByIds;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_GetByIds`(
	IN leagueIds JSON
    /*
		Creator: Hank@20220520
        Revisions:
			- 20220520@Hank: init
            - 20220712@Hank: get only major league
			- 20220808@Brian: get a few columns
    */
)
BEGIN
	DROP TEMPORARY TABLE IF EXISTS tmp_ids;
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_ids(LeagueId BIGINT NOT NULL);
	
	INSERT INTO tmp_ids(LeagueId)
    SELECT LeagueId
    FROM 
		JSON_TABLE
        (
			leagueIds,
            "$[*]"
            COLUMNS
            (
				LeagueId BIGINT  PATH "$"
            )
        ) AS t; 
	
        
	SELECT l.LeagueId, l.CountryIconVN, l.LeagueNameVN, l.LeagueNameEN, l.LeagueFullNameEN, l.LeagueType, l.Icon, l.CurrentSeasonId, l.Country as CountryEN, l.CountryVN, e.IsMajor, e.Priority, c.CountryLogo
	FROM livescore_league l
	INNER JOIN livescore_league_extra e ON l.LeagueId = e.LeagueId
    LEFT JOIN livescore_country c ON c.CountryId = l.CountryId
	WHERE EXISTS (SELECT tmp.LeagueId FROM tmp_ids tmp WHERE l.LeagueId = tmp.LeagueId);
END


//
DELIMITER ;	



DROP PROCEDURE IF EXISTS Livescore_League_Update;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_League_Update`(
	/*
		Creator: Brian@20220808
        Revisions:
			- 20220808@Brian: init
    */
    IN CountryVN VARCHAR(255),
    IN LeagueNameVN VARCHAR(255), 
    IN LeagueId LONG,
    IN CountryIconVN VARCHAR(255), 
    OUT ErrCode SMALLINT,
    OUT ErrMsg VARCHAR(100)    
)
BEGIN
	UPDATE `livescore_league` as le
	SET
	`CountryVN` = CountryVN,
    `LeagueNameVN` = LeagueNameVN,
	`CountryIconVN` = CountryIconVN
	WHERE le.LeagueId = LeagueId;
END

//
DELIMITER ;



