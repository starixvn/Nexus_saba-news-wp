/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_posts_by_subject;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts_by_subject`(	
	/*
		Creator: Brian@20220913
        Revisions:
			- 20220913@Brian: init
    */
    _categorySlug VARCHAR(200),
    _categoryType INT,
    _subjectType INT,
	_toDate DATETIME
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
    CREATE TEMPORARY TABLE tmp_Terms (term_id BIGINT, slug VARCHAR(255) primary key, term_taxonomy_id BIGINT);
    
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
    
	IF _categoryType = 4 AND _subjectType = 2 THEN
		DELETE FROM tmp_Terms AS t WHERE t.slug = 'video-bong-ro' OR t.slug = 'video-quan-vot' OR t.slug = 'video-tong-hop';   
	END IF;
    
    INSERT INTO tmp_PostIds (PostId)
    SELECT p.ID
	FROM wp_posts as p
	WHERE p.post_status = 'publish'
	AND p.ID IN (SELECT tr.object_id from wp_term_relationships as tr 
					INNER JOIN tmp_Cate as c ON c.term_taxonomy_id = tr.term_taxonomy_id);                    
	
	SELECT p.id AS PostId, p.post_title AS Title, CONVERT_TZ(p.post_date_gmt,'+00:00', '+08:00')  AS PublishDate,
		pp.guid as Image, _categoryType AS Category
	FROM wp_posts as p
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
	WHERE EXISTS (SELECT tmp.PostId FROM tmp_PostIds tmp WHERE tmp.PostId = p.ID AND p.post_date_gmt > _toDate)
	ORDER BY p.post_date DESC;	
    
END

//
DELIMITER ;	