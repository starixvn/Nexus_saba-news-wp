DROP PROCEDURE IF EXISTS search_posts_xtest;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `search_posts_xtest`(
	_keyword TEXT,
    _likeKeyWord TEXT,
    _start INT,
    _end INT,
    OUT TotalItems INT
)
BEGIN
    DECLARE keyLength INT;
    DECLARE s TEXT;
    DROP TEMPORARY TABLE IF EXISTS tmpResult;
    CREATE TEMPORARY TABLE tmpResult(
		ID INT,
		post_title VARCHAR(200),
        Score INT,
		post_date datetime,
		post_date_gmt datetime,
		post_content TEXT,
		post_name VARCHAR(200),
		feature_image VARCHAR(200),
		post_excerpt TEXT,
		metadata TEXT,
		comment_count INT
    );
	DROP TEMPORARY TABLE IF EXISTS tmpSearchLike;
	CREATE TEMPORARY TABLE tmpSearchLike(ID INT, Score INT);
    
	SELECT length(_keyword) INTO keyLength;
    IF(keyLength > 1) THEN
		SET @s = CONCAT("INSERT INTO tmpSearchLike(ID, Score)",
						" SELECT p.ID,  3 as Score "
						" FROM wp_posts p",
						" WHERE ", _likeKeyWord ,
						" AND p.post_type = 'post' AND p.post_status = 'publish'");
		PREPARE stmt FROM @s;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
    END IF;
    
    INSERT INTO tmpResult(ID, post_title, Score, post_date, post_date_gmt, post_content, post_name, feature_image, post_excerpt, metadata, comment_count)
	SELECT p.ID, 
    p.post_title, 
    ROUND(MATCH(p.post_title) AGAINST (_keyword IN NATURAL LANGUAGE MODE)) + IFNULL(t.Score, 0) as Score,
    p.post_date, 
    p.post_date_gmt, 
    p.post_content, 
    p.post_name, pp.guid as feature_image, 
    p.post_excerpt,
    pm.meta_value as metadata, p.comment_count
    FROM wp_posts p
    LEFT JOIN tmpSearchLike as t ON t.ID = p.ID
    LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
    LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value and pp.post_type = "attachment"
    LEFT JOIN wp_postmeta as pm ON pm.post_id = m.meta_value AND pm.meta_key = "_wp_attachment_metadata"
    WHERE MATCH(p.post_title, p.post_content) AGAINST (_keyword IN NATURAL LANGUAGE MODE)
    AND p.post_type = 'post' 
    AND p.post_status = 'publish';
    
    SELECT count(*) INTO TotalItems FROM tmpResult;
    
    SELECT * 
    FROM tmpResult t 
    ORDER BY t.Score desc, t.post_date desc
    LIMIt _start, _end;
    
    DROP TEMPORARY TABLE IF EXISTS tmpResult;
    DROP TEMPORARY TABLE IF EXISTS tmpSearchLike;
    
END


//
DELIMITER ;	