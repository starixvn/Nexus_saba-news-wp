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
	DECLARE SQL_query TEXT;
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
	CREATE TEMPORARY TABLE tmpSearchLike(
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
	
	SET @s = CONCAT("INSERT INTO tmpResult(ID, post_title, Score, post_date, post_date_gmt, post_content, post_name, feature_image, post_excerpt, metadata, comment_count)",
					" SELECT p.ID, p.post_title, ROUND(MATCH(p.post_title) AGAINST ('" , _keyword ,"' IN NATURAL LANGUAGE MODE)) + 3 as Score ,
						p.post_date, p.post_date_gmt,  p.post_content, p.post_name, pp.guid as feature_image,  p.post_excerpt, pm.meta_value as metadata, p.comment_count",
					" FROM wp_posts p",
                    " LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = '_thumbnail_id'",
                    " LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value and pp.post_type = 'attachment'",
                    " LEFT JOIN wp_postmeta as pm ON pm.post_id = m.meta_value AND pm.meta_key = '_wp_attachment_metadata'",
                    " WHERE ", _likeKeyWord ,
                    " AND p.post_type = 'post' AND p.post_status = 'publish'",
					" ORDER BY p.post_date desc;");
	
    PREPARE stmt FROM @s;
    
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    INSERT INTO tmpSearchLike(ID, post_title, Score, post_date, post_date_gmt, post_content, post_name, feature_image, post_excerpt, metadata, comment_count)
    SELECT * FROM tmpResult;


    INSERT INTO tmpResult(ID, post_title, Score, post_date, post_date_gmt, post_content, post_name, feature_image, post_excerpt, metadata, comment_count)
	SELECT p.ID, 
    p.post_title, 
    ROUND(MATCH(p.post_title) AGAINST (_keyword IN NATURAL LANGUAGE MODE)) as Score,
    p.post_date, 
    p.post_date_gmt, 
    p.post_content, 
    p.post_name, pp.guid as feature_image, 
    p.post_excerpt,
    pm.meta_value as metadata, p.comment_count
    FROM wp_posts p
    LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
    LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value and pp.post_type = "attachment"
    LEFT JOIN wp_postmeta as pm ON pm.post_id = m.meta_value AND pm.meta_key = "_wp_attachment_metadata"
    WHERE MATCH(p.post_title, p.post_content) AGAINST (_keyword IN NATURAL LANGUAGE MODE)
    AND p.post_type = 'post' 
    AND p.post_status = 'publish'
    AND p.ID NOT IN (select p1.ID FROM tmpSearchLike p1)
    ORDER BY score desc, p.post_date desc;
    
    SELECT count(*) INTO TotalItems FROM tmpResult;
    
    SELECT * 
    FROM tmpResult t 
    ORDER BY t.Score desc
    LIMIt _start, _end;
    
    DROP TEMPORARY TABLE IF EXISTS tmpResult;
    DROP TEMPORARY TABLE IF EXISTS tmpSearchLike;
    
END


//
DELIMITER ;	