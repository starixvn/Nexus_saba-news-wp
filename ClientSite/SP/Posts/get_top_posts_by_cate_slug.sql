/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_top_posts_by_cate_slug;

DELIMITER //


CREATE DEFINER=`root`@`%` PROCEDURE `get_top_posts_by_cate_slug`(
	cateSlug varchar(200),
    start INT,
    end INT
)
BEGIN
	DECLARE category INT;
    DECLARE limitDate datetime;
    SELECT DATE_SUB(UTC_TIMESTAMP, INTERVAL 36 HOUR) INTO limitDate;
    
    SELECT t.term_id INTO category 
    FROM wp_terms t 
    INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND taxonomy = "category" WHERE slug = cateSlug;
    
	SELECT p.id, p.post_title, p.post_content, p.post_date, p.post_date_gmt, p.post_name, pp.guid as feature_image, m1.meta_value as metadata, pm.meta_value as views, p.comment_count
	FROM wp_posts as p
	LEFT JOIN wp_postmeta as pm ON pm.post_id = p.ID AND pm.meta_key = "trx_addons_post_views_count"
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment"
	WHERE p.post_type = "post" AND p.post_status = "publish" AND p.post_date_gmt >= limitDate
	AND p.ID IN (SELECT tr.object_id from wp_term_relationships as tr 
						INNER JOIN wp_term_taxonomy as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy = "category"
						WHERE tt.term_id = category OR tt.parent = category)
	ORDER BY ABS(pm.meta_value) DESC
    LIMIT start,end;
END
//
DELIMITER ;	